&times;
&#9776;
// Create a new Date object
const now = new Date();

// Format the date and time using Intl.DateTimeFormat
const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
const formattedDate = new Intl.DateTimeFormat('en-US', options).format(now);

console.log(formattedDate); // Outputs: Monday, September 23, 2024, 14:35:20 (example)