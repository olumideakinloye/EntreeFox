const socket = io(`${serv}:3000`);
let Times = [];
let new_msg_times = [];
socket.on("chat", (data) => {
  if (
    document.getElementById("chat").style.right === "-100dvw" && !document.getElementById("chat").classList.contains("chat2") || !document.getElementById("chat").style.right && !document.getElementById("chat").classList.contains("chat2")
  ) {
    const containers = document.querySelectorAll('.messagee');
    const container = document.getElementById(`${data.roomID}`);

    container.querySelector(".text .message").innerHTML = data.msg;
    container.querySelector(".unseen .time").classList.add("update_new_msg");
    container.querySelector(".unseen .time").innerHTML = evalMessageTime(
      new Date()
    );
    let index = Array.from(containers).indexOf(container);
    
    new_msg_times[index] = new Date();
    let num = container.querySelector(".unseen .unseen_num");
    if (num) {
      num.textContent = parseInt(num.textContent, 10) + 1;
    } else {
      const unseenElement = document.createElement('p');

// Add the class
unseenElement.classList.add('unseen_num');

// Set the inner text (e.g., "1")
unseenElement.textContent = '1';
      container.querySelector(".unseen").append(unseenElement);
    }
  } else {
    const chat_content = document.getElementById("chat_content");
    textarea.value = "";
    Times.push(new Date());
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
  }
});
socket.on("connect", () => {
  console.log(`You are connected: ${socket.id}`);
});
export function join_rooms(Rooms) {
  Rooms.forEach((romid) => {
    socket.emit("joinRoom", romid);
  });
}
export function leave_rooms(Rooms) {
  Rooms.forEach((romid) => {
    socket.emit("leaveRoom", romid);
  });
}
export function join_room() {
  let parts = PFP.split("/");
  let receiver = parts[parts.indexOf("uploads") + 1];
  let roomid = `${BigInt(sender) + BigInt(receiver)}`;
  socket.emit("joinRoom", roomid);
  // alert(roomid)
}
document.getElementById("send").addEventListener("click", () => {
  if (textarea.value.length > 0) {
    let parts = PFP.split("/");
    let receiver = parts[parts.indexOf("uploads") + 1];
    let roomid = `${BigInt(sender) + BigInt(receiver)}`;
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
    const chat_content2 = document.querySelector(".chat_content");
    chat_content2.scrollTo({
      top: chat_content2.scrollHeight,
      behavior: "smooth",
    });
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

const update_new_msg_time = setInterval(() => {
  let updates2 = document.querySelectorAll(".unseen .time");
  if (updates2) {
    updates2.forEach((time_div2, index) => {
      if(time_div2.classList.contains("update_new_msg")){
        let time2 = new_msg_times[index];
        time_div2.innerHTML = evalMessageTime(time2);
      }
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
// window.join_room = join_room;
// window.join_rooms = join_rooms;
