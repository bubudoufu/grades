"use strict";

// 取得
function fetchResult() {
  return fetch("./grades.php")
    .then((response) => response.json())
    .then((data) => {
      return data;
    })
    .catch((error) => {
      console.log(error);
      return;
    });
}

// 表示
const grades = document.getElementById("grades");

fetchResult().then((data) => {
  Object.keys(data).forEach((key) => {
    const tr = document.createElement("tr");
    const th = document.createElement("th");
    const td = document.createElement("td");

    th.textContent = `${data[key].turn}`;
    tr.appendChild(th);
    td.textContent = `${data[key].win}`;
    tr.appendChild(td);
    const td2 = document.createElement("td");
    td2.textContent = `${data[key].lose}`;
    tr.appendChild(td2);
    const td3 = document.createElement("td");
    td3.textContent = `${data[key].draw}`;
    tr.appendChild(td3);
    grades.appendChild(tr);
  });
});

// 送信
const btn = document.getElementById("btn");
btn.addEventListener("click", () => {
  const turn = document.querySelector(".turn").value;
  const result = document.querySelector(".result").value;
  sendResult(turn, result);
});

// 登録
function sendResult(turn, result) {
  const fd = new FormData();
  fd.append("turn", turn);
  fd.append("result", result);
  fetch("./grades.php", {
    method: "POST",
    body: fd,
  })
    .then((response) => console.log(response))
    .then((data) => console.log(data))
    .then(location.reload())
    .catch((error) => {
      console.log(error);
    });
}


