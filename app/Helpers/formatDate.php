<?php

namespace App\Helpers;

class formatDate
{
    public static function dmy($inputDate)
    {
        // Pisahkan tanggal dan waktu dari string input
        $parts = explode(' ', $inputDate);

        // Pastikan bahwa array $parts memiliki elemen yang cukup
        if (count($parts) >= 2) {
            $datePart = $parts[0]; // Bagian tanggal (misal: "2024-05-10")
            $timePart = $parts[1]; // Bagian waktu (misal: "17:35:00")

            // Bagian-bagian tanggal (tahun, bulan, dan hari)
            $dateComponents = explode('-', $datePart);

            // Pastikan bahwa array $dateComponents memiliki jumlah elemen yang tepat
            if (count($dateComponents) == 3) {
                $year = $dateComponents[0];
                $month = $dateComponents[1];
                $day = $dateComponents[2];

                // Format ulang tanggal menjadi "DD-MM-YYYY HH:mm"
                $formattedDateTime = "$day-$month-$year $timePart";

                return $formattedDateTime;
            }
        }

        // Jika ada masalah dalam pemrosesan, kembalikan nilai kosong atau sesuai kebutuhan
        return '';
    }

    function formatRupiah($angka)
    {
        $rupiah = number_format($angka, 0, ',', '.');
        return 'Rp ' . $rupiah;
    }
}
