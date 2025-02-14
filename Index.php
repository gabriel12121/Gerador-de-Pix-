<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerador de Pix - Mercado Pago</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin: 50px; }
        input, button { margin: 10px; padding: 10px; font-size: 16px; }
        textarea { width: 90%; height: 60px; margin-top: 10px; }
    </style>
</head>
<body>

    <h2>Gerador de Pix - Mercado Pago</h2>
    
    <form id="pixForm">
        <label>Valor do Pix (R$):</label>
        <input type="number" id="valor" name="valor" step="0.01" required>
        <br>
        <button type="submit">Gerar Pix</button>
    </form>

    <div id="resultado" style="display: none;">
        <h3>QR Code para Pagamento:</h3>
        <img id="qrCodeImg" src="" alt="QR Code Pix">
        <h3>Código Copia e Cola:</h3>
        <textarea id="copiaCola" readonly></textarea>
    </div>

    <script>
        document.getElementById("pixForm").addEventListener("submit", function(event) {
            event.preventDefault();
            let valor = document.getElementById("valor").value;
            
            fetch("gerar_pix.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "valor=" + valor
            })
            .then(response => response.json())
            .then(data => {
                if (data.qr_code_base64 && data.qr_code) {
                    document.getElementById("qrCodeImg").src = "data:image/png;base64," + data.qr_code_base64;
                    document.getElementById("copiaCola").value = data.qr_code;
                    document.getElementById("resultado").style.display = "block";
                } else {
                    alert("Erro ao gerar Pix!");
                }
            })
            .catch(error => console.error("Erro:", error));
        });
    </script>

</body>
</html>
