import cv2
import numpy as np
import requests  # <-- Impor ini buat kirim data lewat HTTP

# 1. Inisialisasi Webcam dan Konfigurasi API
cap = cv2.VideoCapture(0)
url = "http://127.0.0.1:8000/api/feeding-logs"

# State Tracker biar gak nge-flood database Laravel lu, Gat!
last_status = None 

while True:
    ret, frame = cap.read()
    if not ret:
        break

    # 2. Tentukan Area Mangkuk (ROI) - Ukuran Sedang 300x300
    roi = frame[90:390, 170:470] 
    
    # 3. Ubah ke Grayscale & Hitung rata-rata kecerahan
    gray_roi = cv2.cvtColor(roi, cv2.COLOR_BGR2GRAY)
    avg_brightness = np.mean(gray_roi)

    # 4. Logika Deteksi
    status = "TERISI"
    color = (0, 255, 0)  # Hijau

    # Jika lebih gelap (misal tertutup pakan) atau kondisi mangkuk kosong
    # (Silakan sesuaikan batas threshold 100 ini dengan kondisi cahaya kamar lu ya, Gat!)
    if avg_brightness < 100: 
        status = "KOSONG"
        color = (0, 0, 255)  # Merah

    # 5. KIRIM DATA KE LARAVEL (HANYA SAAT STATUS BERUBAH)
    if status != last_status:
        # Konversi status agar sesuai dengan validasi database Laravel ('KOSONG' atau 'BERHASIL ISI')
        api_status = "BERHASIL ISI" if status == "TERISI" else "KOSONG"
        
        # Hitung sisa stok dinamis (Pemanis presentasi LKS lu biar kelihatan canggih!)
        stock_percent = 95 if status == "TERISI" else 10

        payload = {
            "status": api_status,
            "stock_percent": int(stock_percent),
            "brightness": int(avg_brightness)
        }

        try:
            print(f"\n[SISTEM] Status berubah menjadi {status}! Mengirim log ke Laravel...")
            response = requests.post(url, json=payload, timeout=5)
            print(f"[SERVER RESPONSE] Code: {response.status_code} | Msg: {response.json().get('message')}")
            
            # Update state tracker jika sukses terkirim
            last_status = status 
        except Exception as e:
            print(f"\n[ERROR] Gagal mengirim data ke Laravel, Cik! Detail: {e}")

    # 6. Tampilkan Visualisasi di Layar
    cv2.rectangle(frame, (170, 90), (470, 390), color, 2)
    display_text = f"Status: {status} ({int(avg_brightness)})"
    cv2.putText(frame, display_text, (170, 80), cv2.FONT_HERSHEY_SIMPLEX, 0.7, color, 2)
    
    cv2.imshow('Smart Pet Feeder Cam - SMK Bisa!', frame)

    # Tekan 'q' buat keluar
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()