<main class="main-content">
    <div class="container">
        <!-- Section de bienvenue -->
        <div class="welcome-section">
            <h1><?= __('welcome_message', $translations); ?></h1>
            <br>
            <p><?= __('game_description', $translations); ?></p>
            <br>
            <a href="register.php" class="register-button"><?= __('start_button', $translations); ?></a>
        </div>
    </div>

    <!-- Section d'informations importantes -->
    <div class="container">
        <div class="attention-section">
            <h1><?= __('attention', $translations); ?></h1>
            <p><?= __('info_weed', $translations); ?></p>
            <p><?= __('info_weed2', $translations); ?></p>
        </div>
    </div>

    <!-- Section des images en dessous -->
    <div class="container">
        <div class="image-row">
            <div class="image-box">
                <img src="/path/to/image1.jpg" alt="Description Image 1">
            </div>
            <div class="image-box">
                <img src="/path/to/image2.jpg" alt="Description Image 2">
            </div>
            <div class="image-box">
                <img src="/path/to/image3.jpg" alt="Description Image 3">
            </div>
            <div class="image-box">
                <img src="/path/to/image4.jpg" alt="Description Image 4">
            </div>
            <div class="image-box">
                <img src="/path/to/image5.jpg" alt="Description Image 5">
            </div>
        </div>
    </div>

</main>
<footer>