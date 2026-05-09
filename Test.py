import requests

# URL API lokal Laravel lu
url = "http://127.0.0.1:8000/api/feeding-logs"

# Data simulasi pakan sukses diisi dari kamera
payload = {
    "status": "BERHASIL ISI",
    "stock_percent": 95,
    "brightness": 140
}

try:
    print("Mencoba mengirim data ke Laravel...")
    response = requests.post(url, json=payload)

    print(f"Status Code: {response.status_code}")
    print("Respon dari Laravel:", response.json())
except Exception as e:
    print("Waduh error, Cik! Cek apakah 'php artisan serve' lu udah jalan.", e)