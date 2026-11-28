# Kebijakan Keamanan

Garuda-Hub adalah aplikasi **pembelajaran** berbasis Laravel yang dipasang di server publik untuk tugas Operation Go-Live. Pengamanan yang diterapkan: query berparameter (Eloquent), escaping keluaran Blade, token CSRF, hash password, pengecekan peran dan kepemilikan di sisi server, rate limit pada login, serta header keamanan seperti Content-Security-Policy. Aplikasi ini belum melalui audit keamanan independen.

## Batasan yang diketahui

- Hanya data dummy; jangan masukkan data pribadi atau password asli.
- Belum ada audit keamanan independen.

## Melaporkan celah

Temuan keamanan boleh dilaporkan karena justru bagian dari tujuan belajar.

1. Jangan membuka isu publik berisi detail eksploit yang bisa dipakai menyerang pihak lain.
2. Laporkan lewat fitur *private vulnerability reporting* GitHub pada repositori ini (tab Security), atau hubungi pemilik repositori lewat profil GitHub.
3. Sertakan langkah reproduksi, halaman terkait, dan dampaknya.

Pengujian hanya boleh dilakukan pada instalasi milik Anda sendiri (lokal atau lingkungan belajar), bukan pada sistem orang lain.

Ini proyek belajar tanpa jaminan waktu tanggapan.
