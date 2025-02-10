function waktu(waktu) {
    const date = new Date(waktu);
    const formattedDate = date.toLocaleString("id-ID", {
        weekday: "long", // Senin, Selasa, dst.
        year: "numeric", // 2025
        month: "long", // Februari
        day: "numeric", // 10
        hour: "2-digit", // 22 (24 jam)
        minute: "2-digit",
        second: "2-digit",
        timeZoneName: "short" // WIB
    });
    return formattedDate;
}
