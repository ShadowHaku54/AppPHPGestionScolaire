document.querySelectorAll("#ov .card tbody tr").forEach((r,i)=>setTimeout(()=>r.classList.add("loaded"),i*90))
