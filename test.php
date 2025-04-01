<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Elapsed Since Event</title>
</head>
<style>
    body,
    html {
        height: 100%;
        margin: 0;
        overflow: hidden;
    }

    #container {
        position: relative;
        width: 100%;
        height: 100%;
    }

    #resizable-div {
        position: absolute;
        bottom: 0;
        height: 50vh;
        /* Initial height */
        width: 100%;
        background-color: lightblue;
        cursor: ns-resize;
        /* Cursor change for resizing */
        transition: all 0.3s;
    }

    .box-container {
        width: 100vw;
        /* height: 50vh; */
        max-height: 80vh;
        background-color: bisque;
        overflow: scroll;
        margin-top: 20px;
    }

    .box {
        width: 100vw;
        height: 20vh;
        background-color: blue;
    }
</style>

<body>
    <div id="container">
        <div id="resizable-div">
            <div class="box-container">
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
                <div class="box">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia vero eos praesentium quod laborum minus repellat optio dignissimos ducimus tempora. Iure nemo quo nihil laboriosam dignissimos similique nostrum ad incidunt.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const resizableDiv = document.getElementById('resizable-div');
        let isResizing = false;
        let startY, startHeight;

        resizableDiv.addEventListener('mousedown', startResize);
        resizableDiv.addEventListener('touchstart', startResize);

        function startResize(e) {
            isResizing = true;
            startY = e.type === 'mousedown' ? e.clientY : e.touches[0].clientY;
            startHeight = resizableDiv.offsetHeight;

            document.addEventListener('mousemove', resize);
            document.addEventListener('touchmove', resize);
            document.addEventListener('mouseup', stopResize);
            document.addEventListener('touchend', stopResize);
        }

        function resize(e) {
            if(e.target.closest('.box-container')){}else{

                if (!isResizing) return;
    
                let currentY = e.type === 'mousemove' ? e.clientY : e.touches[0].clientY;
                let newHeight = startHeight - (currentY - startY);
    
                // Ensure the div's height does not go below a minimum value
                if (newHeight < 50) newHeight = 50;
                if (newHeight > startHeight) {
                    resizableDiv.style.height = `${80}vh`;
                } else {
                    resizableDiv.style.height = `${50}vh`;
                }
            }
        }

        function stopResize() {
            isResizing = false;

            document.removeEventListener('mousemove', resize);
            document.removeEventListener('touchmove', resize);
            document.removeEventListener('mouseup', stopResize);
            document.removeEventListener('touchend', stopResize);
        }
    </script>

</body>

</html>
<!-- <?php
        $name = "Olumide";
        $recipient_email = "olumideakinloye23@gmail.com";
        $subject = 'Change password confirmation code';
        $message = 'This is the message body. You are a fool';

        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        require 'vendor/autoload.php'; // Include Composer's autoloader

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth = true;
            $mail->Username = 'olumideakiloye24@gmail.com'; // SMTP username
            $mail->Password = 'ak47@Nigeria'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable TLS encryption
            $mail->Port = 465; // TCP port to connect to

            //Recipients
            $mail->setFrom('olumideakinloye24@gmail.com', 'Olumide');
            $mail->addAddress($recipient_email, $name);

            //Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
            echo 'Email has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        ?> -->