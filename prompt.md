Bertindaklah sebagai Senior Backend Engineer. Saat ini API saya mengalami Error 500 (Internal Server Error) saat menerima request PUT untuk mengedit data, dan field portfolio_url tidak tersimpan. Tolong perbaiki masalah ini dengan dua instruksi spesifik berikut tanpa saya harus menulis kode manual:

1. Izinkan Field Baru di Model:
Buka file app/Models/ProjectModel.php. Tambahkan string portfolio_url ke dalam array $allowedFields. Ini sangat penting agar CodeIgniter mengizinkan data URL tersebut disimpan ke database.

2. Perbaiki Penanganan Payload PUT di Controller:
Buka file app/Controllers/Api/Projects.php. Temukan fungsi update($id). Masalah Error 500 terjadi karena request PUT dengan payload JSON tidak bisa dibaca menggunakan $this->request->getVar(). Tolong ubah cara penangkapan data di dalam fungsi update menjadi menggunakan $this->request->getJSON(true) (untuk menjadikannya array asosiatif) atau sesuaikan agar data payload JSON berhasil ditangkap dan dilempar ke fungsi update milik Model tanpa menyebabkan crash.