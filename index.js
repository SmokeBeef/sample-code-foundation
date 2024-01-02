fetch("http://localhost:8000/api/penyewaan", {
    body: {
        "pelanggan_id": 2,
        "tglsewa": Date.now(),
        "tglkembali": Date.now()+1000,
        "sttspembayaran": "Lunas",
        "sttskembali": "Belum Kembali",
        "totalharga": 10000,
        "detail": [
            {
                "alat_id": 2,
                "jumlah": 2,
                "subharga": 20000
            }
        ]
    },
    method: "POST",
    headers: {
        "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2FkbWluL2xvZ2luIiwiaWF0IjoxNzAzNzY2NjgwLCJleHAiOjE3MDM3...
        "    
    }
})