document.getElementById('syncBtn').addEventListener('click', function () {
    fetch('/admin/enrollment-sync/run')
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            location.reload();
        });
});
