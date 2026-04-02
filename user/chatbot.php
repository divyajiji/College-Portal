<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Chatbot</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=send" />
   
    <style>
        body {
           font-family: 'Cambria', monospace;
           font-size: 20px;
           margin: 0;
           padding: 0;
           display: flex;
           justify-content: center;
           align-items: center;
           height: 100vh;
           background-color: white;
           
       }

       .chatbot-container {
           width: auto;
           height: 700px;
           border-radius: 20px;
           overflow: hidden;
           display: flex;
           flex-direction: column;
           color: black;
           background-color: white;
       }

       /* .chat-header {
           background: black;
           color: grey;
           padding: 15px;
           text-align: center;
           font-size: 25px;
           font-weight: bolder;
           letter-spacing: 1px;
       } */
       
       .chat-messages {
           flex-grow: 1;
           padding: 15px;
           overflow-y: auto;
           display: flex;
           flex-direction: column;
           gap: 12px;
           background-color: white;
           max-height:75vh;
           scrollbar-width: none;
       }
       .chat-messages::-webkit-scrollbar {
            display: none;
        }

       /* .sidebar {
           width: 250px;
           background-color: black;
           color: grey;
           display: flex;
           flex-direction: column;
           padding: 20px;
           height: 100vh;
           
       }

       .sidebar h2 {
           text-align: center;
           margin-bottom: 20px;
       }
       .sidebar.hidden {
            transform: translateX(-100%);
        }


       .sidebar a {
           color: grey;
           text-decoration: none;
           padding: 10px;
           transition: 0.3s;
           font-size: 25px;
           
       }

       .sidebar a:hover {
           background-color: #005bb5;
       } */

       .message {
           max-width: 75%;
           padding: 15px;
           border-radius: 15px;
           line-height: 1.6;
       }

       .user-message {
           align-self: flex-end;
           color: black;
       }
       .user-message.message{
           font-size: 25px;
       }

       .bot-message {
           align-self: flex-start;
           color: grey;
       }
       .bot-message.message{
        font-size: 25px;
       }

       .faq-container {
           padding: 12px;
           text-align: center;
       }

       .faq-container h3 {
           margin-bottom: 10px;
           color: #0078d4;
       }
       .faq-container.hidden {
            opacity: 0;
            pointer-events: none;
        }
       .faq-buttons {
           display: grid;
           grid-template-columns: repeat(4, 1fr);
           gap: 15px;
           justify-content: center;
       }

       .faq-buttons button {
           padding: 18px;
           font-style: oblique;
           font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            border: none; 
           /*border-radius: 5px; */
           /* background: linear-gradient(90deg, #0078d4, #00c6ff); */
           color: grey;
           font-size: 20px;
           cursor: pointer;
           transition: background 0.1s ease;
           width: 100%;
       }

       .chat-input {
           display: flex;
           padding: 40px;
           /* border-top: 2px solid #ddd; */
          /* background: white; */
           position: sticky;
           bottom: 0;
           width: 85%;
       }

       .chat-input input {
           flex: 1;
           padding: 18px;
           border: 1px solid #ddd;
           border-style: solid;
           /* border-radius: 30px; */
           font-size: 20px;
           outline: none;
           transition: border 0.3s ease;
       }

       .chat-input button {
           padding: 12px 18px;
           margin-left: 10px;
           border: none;
           border-radius: 20px;
           background: linear-gradient(90deg, #0078d4, #00c6ff);
           color: white;
           font-size: 16px;
           cursor: pointer;
           transition: background 0.3s ease;
       }
       .chat-input button:hover {
            transform: scale(1.1);
        }


   </style>
</head>
<body>
   <!-- <div class="sidebar" id="sidebar">
       <h2>Menu</h2>
       <a href="#">Home</a>
       <a href="#">FAQ</a>
       <a href="#">Settings</a>
   </div> -->
   <?php
   $pid=$_SESSION['uid'];
   $ret=mysqli_query($con,"select * from tbluser where ID='$pid'");
   $cnt=1;
   while ($row=mysqli_fetch_array($ret)) {
   
   ?>  
   <div class="chatbot-container" id="chatbotContainer">
       
       <h2 style="color: grey; font-family: Verdana, Geneva, Tahoma, sans-serif;">Hello <?php echo $row['FirstName'];?>,<br>How can I help you?</h2>
       <?php } ?>  
       <div class="faq-container" id="faqContainer">
           <h3>Frequently Asked Questions</h3>
           <div class="faq-buttons">
               <button onclick="faqButtonClick('What are the cutoff details?')">What are the cutoff details?</button>
               <button onclick="faqButtonClick('How does the Counseling process work?')">How does the Counseling process work?</button>
               <button onclick="faqButtonClick('What are the fees for different programs?')">What are the fees for different programs?</button>
               <button onclick="faqButtonClick('How many seats are available in each program?')">How many seats are available in each program?</button>
           </div>
       </div>
       <div class="chat-messages" id="chatMessages"></div>
       <div class="chat-input">
           <input type="text" id="userInput" placeholder="Type your message..." onfocus="collapseUI()">
           <button id="voiceBtn"><i class="fas fa-microphone"></i></button>
           <button class="material-symbols-outlined" onclick="sendMessage()"> send </button>
       </div>
   </div>
    </div>
    <script>

        const voiceBtn = document.getElementById('voiceBtn');
        const userInput = document.getElementById("userInput");
            document.getElementById("userInput").addEventListener("keydown", function(event) {
                if (event.key === "Enter") {  
                    sendMessage();
                }
            });
        if ('webkitSpeechRecognition' in window) {
            const recognition = new webkitSpeechRecognition();
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.lang = 'en-US';
        
            voiceBtn.addEventListener('click', () => {
                recognition.start();
                
            });
        
            recognition.onresult = (event) => {
                userInput.value = event.results[0][0].transcript;
            };
        
            recognition.onerror = (event) => {
                console.error('Speech recognition error:', event.error);
            };
        } else {
            alert("Your browser doesn't support voice input.");
        }
        
        
 function faqButtonClick(question) {
    document.getElementById('userInput').value = question;
    sendMessage(); 
} 
function collapseUI() {
           
            document.getElementById('faqContainer').classList.add('hidden');
            document.getElementById('chatbotContainer').style.width = '90%';
        }
async function sendMessage() {
    const chatMessages = document.getElementById('chatMessages');
    const userMessage = userInput.value.trim();

    if (userMessage) {
        
        const userMessageDiv = document.createElement('div');
        userMessageDiv.className = 'user-message message';
        userMessageDiv.textContent = userMessage;
        chatMessages.appendChild(userMessageDiv);

        userInput.value = '';
        userInput.focus();
        scrollToBottom(); 

        try {
            
            const response = await fetch('http://localhost:5005/webhooks/rest/webhook', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    sender: "user123",  
                    message: userMessage
                }),
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();
            
            // Display Bot Response
            data.forEach((msg) => {
                const botMessageDiv = document.createElement('div');
                botMessageDiv.className = 'bot-message message';
                botMessageDiv.textContent = msg.text;
                chatMessages.appendChild(botMessageDiv);
                scrollToBottom(); // Scroll to latest message
            });

        } catch (error) {
            console.error('Error:', error);
            const errorMessageDiv = document.createElement('div');
            errorMessageDiv.className = 'bot-message message';
            errorMessageDiv.textContent = "Sorry, something went wrong.";
            chatMessages.appendChild(errorMessageDiv);
            scrollToBottom(); // Scroll to latest message
        }
    }
}

// Function to auto-scroll to the latest message
function scrollToBottom() {
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
}


        </script>
</body>
</html>
