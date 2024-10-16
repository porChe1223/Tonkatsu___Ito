// 要素を取得
const responseArea = document.getElementById('response_area'); // 左側のエリア
const responceAreaRight = document.getElementById('responce_area'); // 右側のエリア
const chatText = document.getElementById('chat-message-text');//テキスト
const sendButton = document.getElementById('chat-message-send-button');//送信ボタン

// ボタンがクリックされたときに実行
sendButton.addEventListener('click', () => {
  const message = chatText.value; // テキストボックスの内容を取得

  if (message.trim() === "") {
    responseArea.textContent = "メッセージを入力してください。";
    return; // メッセージが空の場合は送信しない
  }

  // POSTリクエストを送信
  const url = 'localhost/gameroom'; // POSTリクエスト先

    fetch(url, {
        method: 'POST', // POSTメソッドを指定
        headers: {
            'Content-Type': 'application/json' // JSON形式で送信する
        },
        // フィールド名を "content" に変更
    })
    .then(response => response.text())
    .then(data => {
        //チャット(1往復分)
        const Chat = document.getElementById('chat');
        Chat.className = 'chat-container';
        
        //質問側(右)      
        const chatClear = document.createElement('div');
        chatClear.className = 'chat_clear';
        Chat.appendChild(nchatClear);

        const chatRight = document.createElement('div');
        chatRight.className = 'chat_right';
        Chat.appendChild(chatRight);

        const chatText = document.createElement('div');
        chatText.className = 'chat_text';
        chatText.innerHTML = `${message}`;  //ココテーブル作成！！
        chatRight.appendChild(chatText);

        //返答側(左)
        const chatClear = document.createElement('div');
        chatClear.className = 'chat_clear';
        Chat.appendChild(chatClear);

        const chatLeft = document.createElement('div');
        chatLeft.className = 'chat_left';
        Chat.appendChild(chatLeft);

        const chatText = document.createElement('div');
        chatText.className = 'chat_text';
        chatText.innerHTML = `${message}`;  //ココテーブル作成！！
        chatLeft.appendChild(chatText);
    })

    .catch(error => {
      console.error('Error:', error);  // エラーが発生した場合にコンソールに表示
      const errorMessage = document.createElement('div');
      errorMessage.className = 'error';
      errorMessage.innerHTML = `<p>エラーが発生しました。</p>`;
      responseArea.appendChild(errorMessage);  // エラーを独立して表示
    });

  // メッセージボックスをクリア
  messageBox.value = "";
});
