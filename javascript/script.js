document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault(); // Evita recarregar a página

    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;
    let errorMessage = document.getElementById("error-message");

    // Login fixo só para teste
    if (username === "admin" && password === "1234") {
        alert("Login realizado com sucesso!");
        window.location.href = "home.html"; // página de destino
    } else {
        errorMessage.textContent = "Usuário ou senha incorretos!";
    }
});
