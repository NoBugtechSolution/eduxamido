<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0" /> -->
    <title>Eduxamido</title>
    <link rel="stylesheet" href="loginstyle.css?v=<?= time() ?>" />
</head>

<body>
    <div class="wrapper">
        <div class="form-wrapper sign-in">
            <form action="login_check.php" method="POST">
                <h2>Admin Login</h2>

                <div class="input-group">
                    <input type="email" name="email" required placeholder='' value='admin@mail.com'>
                    <label for="email">Email</label>
                </div>
                <div class="input-group">
                    <input type="password" name="password" required placeholder=''>
                    <label for="password">Password</label>
                </div>

                <?php
                if (isset($_GET['error'])) {
                    echo '<p class="error-message">' . htmlspecialchars($_GET['error']) . '</p>';
                ?>
                    <div id='insta-main'>
                        <div class='insta-box'>
                            <span class="close"><ion-icon onclick="closeTab()" name="close-outline"></ion-icon></span>
                            <h3>Login Failed</h3>
                            <h4>Try contacting</h4>
                            <a target='_black' href="https://www.instagram.com/nobugtechsolution/"><img src="../Common/Assets/Logo.jpg" alt="" srcset=""></a>
                            <a target='_black' href="https://www.instagram.com/nobugtechsolution/"><span class='title'><ion-icon name="logo-instagram"></ion-icon>nobugtechsolution</span></a>
                        </div>
                    </div>
                <?php
                }
                ?>
                <button type="submit" class="btn">Login</button>
            </form>


        </div>
    </div>
    <script>
        const tab = document.getElementById("insta-main");

        function closeTab() {
            tab.style.display = 'none'
        }
    </script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="script.js"></script>
</body>

</html>