const canvas = document.getElementById('bg');
const ctx = canvas.getContext('2d');
let w, h;
function resize() {
    w = canvas.width = window.innerWidth;
    h = canvas.height = window.innerHeight;
}
window.addEventListener('resize', resize);
resize();

let lines = Array.from({length: 60}, () => ({
    x: Math.random() * w,
    y: Math.random() * h,
    dx: (Math.random() - 0.5) * 0.5,
    dy: (Math.random() - 0.5) * 0.5
}));

function animate() {
    ctx.fillStyle = 'rgba(0,0,0,0.15)';
    ctx.fillRect(0, 0, w, h);
    ctx.strokeStyle = '#888';
    lines.forEach(p => {
        ctx.beginPath();
        ctx.moveTo(p.x, p.y);
        p.x += p.dx;
        p.y += p.dy;
        if (p.x < 0 || p.x > w) p.dx *= -1;
        if (p.y < 0 || p.y > h) p.dy *= -1;
        ctx.lineTo(p.x, p.y);
        ctx.stroke();
    });
    requestAnimationFrame(animate);
}
animate();