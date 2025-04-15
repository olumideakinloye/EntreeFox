const socket = io(`${serv}:3000`);
let Times = [];
socket.on("chat", (data) => {
  // console.log(data);
  const chat_content = document.getElementById("chat_content");
  textarea.value = "";
  Times.push(new Date());
  console.log(Times);
  if (data.sender === sender) {
    chat_content.innerHTML += `<div class="messages sender">
            <div class="msg">
                <p>${data.msg}</p>
                <span class="msg_tail">

                </span>
            </div>
            <p class="time update">${evalMessageTime(new Date())}</p>
        </div>`;
  } else if (data.receiver === sender) {
    chat_content.innerHTML += `<div class="messages">
            <div class="msg">
                <p>${data.msg}</p>
                <span class="msg_tail">

                </span>
            </div>
            <p class="time update">${evalMessageTime(new Date())}</p>
        </div>`;
  }
  const chat_content2 = document.querySelector(".chat_content");
  chat_content2.scrollTo({
    top: chat_content2.scrollHeight,
    behavior: "smooth",
  });
});
socket.on("connect", () => {
  console.log(`You are connected: ${socket.id}`);
});
export function join_room() {
  let parts = PFP.split("/");
  let receiver = parts[parts.indexOf("uploads") + 1];
  let roomid = Number(sender) + Number(receiver);
  socket.emit("joinRoom", roomid);
  // alert(roomid)
}
document.getElementById("send").addEventListener("click", () => {
  if (textarea.value.length > 0) {
    let parts = PFP.split("/");
    let receiver = parts[parts.indexOf("uploads") + 1];
    let roomid = Number(sender) + Number(receiver);
    // alert(roomid)
    const msg = textarea.value.replace(/\n/g, "<br>");
    socket.emit("chat", {
      roomID: roomid,
      sender: sender,
      receiver: receiver,
      msg: msg,
    });
    textarea.style.height = "";
    const chat_content = document.getElementById("chat_content");
    chat_content.innerHTML += `<div class="messages sender">
            <div class="msg">
                <p>${msg}</p>
                <span class="msg_tail">

                </span>
            </div>
            <p class="time update">${evalMessageTime(new Date())}</p>
        </div>`;
    Times.push(new Date());
    console.log(Times);
    fetch(`${serv}:8080/EntreeFox/Server_side/save_messages.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        sender: sender,
        receiver: receiver,
        msg: msg,
      }),
    })
      .then((res) => res.text())
      .then(console.log)
      .catch(console.error);
    textarea.value = "";
  }
});
function evalMessageTime(datetime) {
  const now = new Date();
  const time = new Date(datetime);
  const isFuture = time > now;

  const diffMs = Math.abs(now - time);
  const diffSeconds = Math.floor(diffMs / 1000);
  const diffMinutes = Math.floor(diffSeconds / 60);
  const diffHours = Math.floor(diffMinutes / 60);
  const diffDays = Math.floor(diffHours / 24);

  const suffix = isFuture ? "from now" : "ago";

  const isToday = now.toDateString() === time.toDateString();

  if (
    now.getFullYear() !== time.getFullYear() ||
    now.getMonth() !== time.getMonth() ||
    diffDays > 7
  ) {
    // Return exact date for older dates
    return (
      time.getDate().toString().padStart(2, "0") +
      "-" +
      (time.getMonth() + 1).toString().padStart(2, "0") +
      "-" +
      time.getFullYear()
    );
  } else if (diffDays > 0) {
    if (diffDays === 1) {
      const timeString = time.toLocaleTimeString([], {
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
      });
      return isFuture
        ? `Tomorrow at ${timeString}`
        : `Yesterday at ${timeString}`;
    }
    return `${diffDays} day${diffDays > 1 ? "s" : ""} ${suffix}`;
  } else if (diffHours > 0) {
    if (isToday && !isFuture) {
      const timeString = time.toLocaleTimeString([], {
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
      });
      return `Today at ${timeString}`;
    }
    return `${diffHours} hour${diffHours > 1 ? "s" : ""} ${suffix}`;
  } else if (diffMinutes > 0) {
    return `${diffMinutes} minute${diffMinutes > 1 ? "s" : ""} ${suffix}`;
  } else if (diffSeconds > 10) {
    return `${diffSeconds} seconds ${suffix}`;
  } else if (diffSeconds >= 0) {
    return isFuture ? "In a moment" : "Just now";
  }

  return "Unknown time";
}
const update_time = setInterval(() => {
  let updates = document.querySelectorAll(".update");
  if (updates) {
    updates.forEach((time_div, index) => {
      let time = Times[index];
      time_div.innerHTML = evalMessageTime(time);
    });
  }
}, 60000);

// update_time();
function generate_msgid(limit) {
  const chars = [
    0,
    1,
    2,
    3,
    4,
    5,
    6,
    7,
    8,
    9,
    "a",
    "b",
    "c",
    "d",
    "e",
    "f",
    "g",
    "h",
    "i",
    "j",
    "k",
    "l",
    "m",
    "n",
    "o",
    "p",
    "q",
    "r",
    "s",
    "t",
    "u",
    "v",
    "w",
    "x",
    "y",
    "z",
    "A",
    "B",
    "C",
    "D",
    "E",
    "F",
    "G",
    "H",
    "I",
    "J",
    "K",
    "L",
    "M",
    "N",
    "O",
    "P",
    "Q",
    "R",
    "S",
    "T",
    "U",
    "V",
    "W",
    "X",
    "Y",
    "Z",
  ];

  let text = "";
  for (let i = 0; i < limit; i++) {
    const random = Math.floor(Math.random() * chars.length);
    text += chars[random];
  }
  return text;
}
window.join_room = join_room;
