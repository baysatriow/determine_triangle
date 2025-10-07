<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>DETERMINE TRIANGLE</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
@keyframes fadeIn {
  from {opacity: 0; transform: translateY(10px);}
  to {opacity: 1; transform: translateY(0);}
}
.animate-fadeIn { animation: fadeIn 0.6s ease-in-out; }
canvas { display: block; margin: 0 auto; border-radius: 10px; }
</style>
</head>
<body class="bg-gradient-to-br from-[#0a0f1f] via-[#141d2f] to-[#1f2937] flex items-center justify-center min-h-screen text-white font-['Poppins']">

<div class="bg-[#0f172a]/80 border border-[#334155] backdrop-blur-xl rounded-2xl shadow-2xl p-8 w-[480px] text-center">
  <h1 class="text-2xl font-bold text-cyan-400 mb-1 tracking-wide">🔺 DETERMINE TRIANGLE</h1>
  <p class="text-sm text-gray-400 mb-6 italic">Analisis dan visualisasi segitiga secara ilmiah</p>

  <form method="post" class="space-y-5">
    <div class="flex justify-between">
      <?php
      $inputs = ["a" => "Sisi A", "b" => "Sisi B", "c" => "Sisi C"];
      foreach ($inputs as $name => $label) {
        echo "
        <div class='flex flex-col items-center'>
          <label for='$name' class='text-xs text-cyan-300 mb-1 uppercase'>$label</label>
          <input type='number' step='0.01' name='$name' required
            class='border border-gray-600 bg-[#1e293b] text-cyan-100 rounded-lg w-24 text-center p-2 focus:ring-2 focus:ring-cyan-500 outline-none transition'>
        </div>";
      }
      ?>
    </div>

    <button type="submit"
      class="w-full bg-gradient-to-r from-cyan-400 to-blue-500 hover:from-cyan-300 hover:to-blue-400 text-black font-semibold py-2 rounded-lg shadow-md transition-all">
      Analisis Segitiga
    </button>
  </form>

<!-- GACOR KANG  MASKENTIR ENGINE CUYY :) -->
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $a = floatval($_POST['a']);
  $b = floatval($_POST['b']);
  $c = floatval($_POST['c']);

  if ($a <= 0 || $b <= 0 || $c <= 0) {
      $type = "❌ Tidak dapat membentuk segitiga (ada sisi ≤ 0)";
      $valid = false;
  } else {
      $sides = [$a, $b, $c];
      sort($sides);
      [$x, $y, $z] = $sides;

      if ($z >= $x + $y) {
          $type = "❌ Tidak dapat membentuk segitiga (sisi terbesar ≥ jumlah dua sisi lainnya)";
          $valid = false;
      } else {
          function almostEqual($a, $b, $tol = 0.01) {
              $avg = ($a + $b) / 2.0;
              return abs($a - $b) / $avg <= $tol;
          }

          if (almostEqual($x, $y) && almostEqual($y, $z)) {
              $type = "🔹 Segitiga SAMA SISI (EQUILATERAL)";
              $color = "#22d3ee";
          } elseif (almostEqual($x, $y) || almostEqual($y, $z) || almostEqual($x, $z)) {
              $type = "🟣 Segitiga SAMA KAKI (ISOSCELES)";
              $color = "#a78bfa";
          } elseif (abs($z*$z - ($x*$x + $y*$y)) < 0.01) {
              $type = "🟩 Segitiga SIKU-SIKU (RIGHT TRIANGLE)";
              $color = "#4ade80";
          } else {
              $type = "🟠 Segitiga BEBAS (SCALENE)";
              $color = "#facc15";
          }
          $valid = true;
      }
  }

  echo "<div class='mt-6 animate-fadeIn'>";
  echo "<div class='text-lg font-semibold text-cyan-300 mb-3'>$type</div>";

  if ($valid) {
    echo "<canvas id='triangleCanvas' width='360' height='280' class='bg-[#0a1220] border border-gray-700 shadow-inner'></canvas>";
    echo "<script>
      const a = $a, b = $b, c = $c;
      const canvas = document.getElementById('triangleCanvas');
      const ctx = canvas.getContext('2d');
      const color = '$color';

      ctx.clearRect(0,0,canvas.width,canvas.height);

      // Grid
      ctx.strokeStyle = '#1e293b';
      for(let i=20;i<canvas.width;i+=20){
        ctx.beginPath(); ctx.moveTo(i,0); ctx.lineTo(i,canvas.height); ctx.stroke();
      }
      for(let j=20;j<canvas.height;j+=20){
        ctx.beginPath(); ctx.moveTo(0,j); ctx.lineTo(canvas.width,j); ctx.stroke();
      }

      // Hukum Cosinus
      const angleC = Math.acos((a*a + b*b - c*c) / (2*a*b));
      const scale = 200 / Math.max(a,b,c);

      // Centering segitiga
      const offsetX = (canvas.width - b*scale)/2;
      const offsetY = 220;

      const xA = offsetX;
      const yA = offsetY;
      const xB = offsetX + b * scale;
      const yB = offsetY;
      const xC = offsetX + a * scale * Math.cos(angleC);
      const yC = offsetY - a * scale * Math.sin(angleC);

      // Shadow dan gradient
      const grad = ctx.createLinearGradient(xA,yA,xB,yC);
      grad.addColorStop(0, color + '66');
      grad.addColorStop(1, color + 'aa');
      ctx.fillStyle = grad;
      ctx.strokeStyle = color;
      ctx.lineWidth = 2;

      // Gambar segitiga
      ctx.beginPath();
      ctx.moveTo(xA,yA);
      ctx.lineTo(xB,yB);
      ctx.lineTo(xC,yC);
      ctx.closePath();
      ctx.fill();
      ctx.stroke();

      // Titik
      ctx.fillStyle = color;
      [[xA,yA,'A'],[xB,yB,'B'],[xC,yC,'C']].forEach(([x,y,l])=>{
        ctx.beginPath(); ctx.arc(x,y,4,0,2*Math.PI); ctx.fill();
        ctx.fillStyle = '#e2e8f0';
        ctx.font = '12px monospace';
        ctx.fillText(l, x-10, y-8);
        ctx.fillStyle = color;
      });

      // Label sisi
      ctx.fillStyle = '#94a3b8';
      ctx.font = '11px monospace';
      ctx.fillText('a=' + a.toFixed(2), (xB + xC)/2 - 10, (yB + yC)/2 - 5);
      ctx.fillText('b=' + b.toFixed(2), (xA + xC)/2 - 10, (yA + yC)/2 - 5);
      ctx.fillText('c=' + c.toFixed(2), (xA + xB)/2 - 10, yA + 15);

      // Branding besar di tengah bawah
      ctx.shadowColor = '#22d3ee';
      ctx.shadowBlur = 10;
      ctx.fillStyle = '#22d3ee';
      ctx.font = 'bold 20px Courier New';
      ctx.textAlign = 'center';
      ctx.fillText('MasKentir Engine', canvas.width / 2, canvas.height - 20);
      ctx.shadowBlur = 0;
    </script>";
  }

  echo "</div>";
}
?>
</div>

</body>
</html>
