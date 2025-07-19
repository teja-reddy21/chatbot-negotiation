function sendOffer() {
  let userInput = document.getElementById("userInput").value;
  if (!userInput) return;

  fetch("negotiate.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "offer=" + encodeURIComponent(userInput)
  })
  .then(res => res.text())
  .then(data => {
    document.getElementById("chatLog").innerHTML += `<p><b>You:</b> ₹${userInput}</p>`;
    document.getElementById("chatLog").innerHTML += `<p><b>Bot:</b> ${data}</p>`;
    document.getElementById("userInput").value = "";
  });
}
