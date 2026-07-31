<!DOCTYPE html>
<html>

<head>

    <title>Vivium Assistant</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css','resources/js/app.js'])

</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="mx-auto" style="max-width:700px;">

            <h2 class="text-center mb-4">
                Vivium Assistant
            </h2>

            <div
                id="chat-box"
                class="border rounded bg-white p-3 mb-3"
                style="height:300px;overflow-y:auto; position: relative;">

                <div class="mb-3">

                    <div class="bg-secondary text-white rounded p-2 d-inline-block">

                        Hello! Welcome to Vivium.
                        How can I help you today?

                    </div>

                </div>

                <div id="typing" style="display:none;">
                    <div class="bg-light border rounded p-2 d-inline-block">
                        <span class="spinner-border spinner-border-sm"></span>
                        Vivium Assistant is typing...
                    </div>
                </div>
            </div>


            <form id="chat-form">

                @csrf

                <div class="input-group">

                    <input
                        id="message"
                        class="form-control"
                        placeholder="Type your message...">

                    <button
                        class="btn btn-primary">
                        Send
                    </button>

                </div>

            </form>

        </div>

    </div>

    <script>
        document.getElementById('chat-form').addEventListener('submit', async function(e) {

            e.preventDefault();

            const input = document.getElementById('message');
            const button = this.querySelector('button');
            const typing = document.getElementById('typing');
            const chatBox = document.getElementById('chat-box');

            const userMessage = input.value.trim();

            if (!userMessage) return;

            // User message
            const userDiv = document.createElement('div');
            userDiv.className = 'text-end mb-2';

            userDiv.innerHTML = `
        <span class="bg-primary text-white rounded p-2 d-inline-block">
            ${userMessage}
        </span>
    `;

            chatBox.appendChild(userDiv);

            chatBox.scrollTop = chatBox.scrollHeight;

            input.value = "";

            // Disable button
            button.disabled = true;

            // Show typing

            typing.id = "typing";

            typing.className = "text-start mb-2";

            typing.innerHTML = `
    <div class="bg-secondary text-white rounded p-2 d-inline-block">
        <span class="spinner-border spinner-border-sm me-2"></span>
        Vivium Assistant is typing...
    </div>
`;

            chatBox.appendChild(typing);

            chatBox.scrollTop = chatBox.scrollHeight;

            chatBox.scrollTop = chatBox.scrollHeight;

            const token = document.querySelector('meta[name="csrf-token"]').content;

            try {

                const response = await fetch('/chat', {

                    method: 'POST',

                    headers: {

                        'Content-Type': 'application/json',

                        'X-CSRF-TOKEN': token

                    },

                    body: JSON.stringify({

                        message: userMessage

                    })

                });

                const data = await response.json();

                // Hide typing
                typing.remove();

                const aiDiv = document.createElement('div');

                aiDiv.className = 'text-start mb-2';

                aiDiv.innerHTML = `
            <div class="bg-secondary text-white rounded p-2 d-inline-block markdown-body">

                ${window.marked
                    ? window.marked.parse(data.reply)
                    : data.reply}

            </div>
        `;

                chatBox.appendChild(aiDiv);

            } catch (err) {

                typing.remove();

                console.error(err);

                const errorDiv = document.createElement('div');

                errorDiv.className = 'text-start text-danger mb-2';

                errorDiv.innerHTML = `
            Error contacting assistant.
            Please try again.
        `;

                chatBox.appendChild(errorDiv);

            } finally {

                button.disabled = false;

                input.focus();

                chatBox.scrollTop = chatBox.scrollHeight;

            }

        });
    </script>


</body>

</html>