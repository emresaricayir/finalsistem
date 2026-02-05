<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg p-6">
            <div class="text-center mb-6">
                <i class="fab fa-whatsapp text-4xl text-green-600 mb-4"></i>
                <h1 class="text-2xl font-bold text-gray-800">WhatsApp Test</h1>
                <p class="text-gray-600">Test mesajı gönder</p>
            </div>

            <form id="whatsappForm" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Telefon Numarası</label>
                    <input type="text" name="phone" id="phone"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                           placeholder="+49123456789" required>
                    <p class="text-xs text-gray-500 mt-1">Örnek: +49123456789</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mesaj</label>
                    <textarea name="message" id="message" rows="4"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                              placeholder="Mesajınızı yazın..." required>Merhaba! Bu bir test mesajıdır.</textarea>
                </div>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                    <i class="fab fa-whatsapp mr-2"></i>
                    WhatsApp Mesajı Gönder
                </button>
            </form>

            <div id="result" class="mt-4 hidden"></div>

            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-yellow-600 mt-1 mr-2"></i>
                    <div class="text-sm text-yellow-800">
                        <p class="font-medium mb-1">Önemli Not:</p>
                        <p>Bu WhatsApp Web API ücretsiz bir test implementasyonudur. Rate limit: 5 mesaj/dakika</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('whatsappForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        const resultDiv = document.getElementById('result');

        // Loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Gönderiliyor...';
        submitBtn.disabled = true;

        fetch('{{ route("whatsapp.send") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            resultDiv.classList.remove('hidden');

            if (data.success) {
                resultDiv.innerHTML = `
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            <span class="text-green-800 font-medium">${data.message}</span>
                        </div>
                        <p class="text-green-600 text-sm mt-1">Telefon: ${data.phone}</p>
                        <p class="text-green-600 text-xs mt-1">Not: Bu bir test implementasyonudur</p>
                    </div>
                `;
            } else {
                resultDiv.innerHTML = `
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
                            <span class="text-red-800 font-medium">${data.error}</span>
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            resultDiv.classList.remove('hidden');
            resultDiv.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
                        <span class="text-red-800 font-medium">Bir hata oluştu: ${error.message}</span>
                    </div>
                </div>
            `;
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
    </script>
</body>
</html>
