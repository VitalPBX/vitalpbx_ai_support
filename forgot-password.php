<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <!-- Page Title -->
    <meta charset="UTF-8">
    <!-- Character Encoding Declaration -->
    <link rel="stylesheet" href="./css/style.css">
    <style>
        /* Aqu� puedes agregar estilos CSS adicionales o incluirlos en tu archivo CSS externo */
    </style>
</head>
<body>

    <div class="chat-header">
        <div class="header-left">
            <a href="index.php">Home</a> <!-- Enlace a la p�gina principal -->
        </div>
        <div class="header-title">VitalPBX Agent AI</div>
        <div class="header-right">

        <!-- Conditional content based on user login status -->
        <?php if (isset($user)): ?>
            <a href="logout.php">Logout</a> <!-- Logout -->
        <?php else: ?>
            <a href="login.php">Login</a> <!-- Login -->
        <?php endif; ?>

        </div>
    </div>

    <h1>Forgot Password</h1> <!-- T�tulo de la p�gina -->

    <!-- Formulario para solicitar el restablecimiento de contrase�a -->
    <form method="post" action="send-password-reset.php">
        <!-- Campo de entrada para el email -->
        <label for="email">email</label>
        <input type="email" name="email" id="email">

        <!-- Bot�n de env�o -->
        <button>Send</button>
    </form>

    <!-- Pie de p�gina con informaci�n adicional -->
    <div class="chat-footer">
        VitalPBX Agent AI (Powered by ChatGPT) can make mistakes. Consider checking important information.
    </div>

</body>
</html>
