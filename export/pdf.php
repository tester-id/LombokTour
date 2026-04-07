<?php
// export/pdf.php

// 1. Cek Library
if (!file_exists('../libs/fpdf/fpdf.php')) {
    die("Error: Library FPDF tidak ditemukan. Pastikan file ada di libs/fpdf/fpdf.php");
}

require('../libs/fpdf/fpdf.php');
require_once '../config/database.php';

// 2. Cek Sesi Login
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$username = $_SESSION['username'] ?? 'User';

// 3. Setting Timezone ke Lombok (WITA)
date_default_timezone_set('Asia/Makassar');

class PDF extends FPDF
{
    var $widths;
    var $aligns;
    
    public $username;

    // Custom Header
    function Header()
    {
        // Logo (Opsional, uncomment jika ada file logo)
        // $this->Image('../public/img/logo.png',10,6,30);
        
        $this->SetFont('Arial','B',16);
        $this->Cell(0,10,'LOMBOK TOUR - DATA PARIWISATA',0,1,'C');
        
        $this->SetFont('Arial','I',10);
        $this->Cell(0,10,'Laporan Data Objek Wisata - Provinsi Nusa Tenggara Barat',0,1,'C');
        
        // Garis pemisah header
        $this->SetLineWidth(0.5);
        $this->Line(10, 32, 287, 32); // Lebar garis disesuaikan A4 Landscape (297mm - margin)
        $this->Ln(10);
    }

    // Custom Footer
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Halaman '.$this->PageNo().'/{nb} | Dicetak oleh '.$this->username.' pada: ' . date('d F Y H:i'). ' WITA',0,0,'C');
    }
    
    // --- FUNGSI TAMBAHAN UNTUK WRAPPING TEXT ---
    
    function SetWidths($w) {
        // Set lebar kolom
        $this->widths=$w;
    }

    function SetAligns($a) {
        // Set perataan teks (L, C, R)
        $this->aligns=$a;
    }

    function Row($data) {
        // Hitung tinggi baris berdasarkan konten terbanyak
        $nb=0;
        for($i=0;$i<count($data);$i++)
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        $h=5*$nb; // 5 adalah tinggi per satu baris teks
        
        // Cek apakah muat di halaman ini, jika tidak buat halaman baru
        $this->CheckPageBreak($h);
        
        // Gambar sel
        for($i=0;$i<count($data);$i++) {
            $w=$this->widths[$i];
            $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            
            // Simpan posisi x dan y saat ini
            $x=$this->GetX();
            $y=$this->GetY();
            
            // Gambar border kotak
            $this->Rect($x,$y,$w,$h);
            
            // Cetak teks (MultiCell otomatis wrap)
            $this->MultiCell($w,5,$data[$i],0,$a);
            
            // Geser posisi ke kanan untuk sel berikutnya
            $this->SetXY($x+$w,$y);
        }
        // Pindah ke baris baru setinggi row yang baru dibuat
        $this->Ln($h);
    }

    function CheckPageBreak($h) {
        // Jika tinggi baris melebihi sisa halaman, tambah halaman baru
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w,$txt) {
        // Menghitung berapa baris yang dibutuhkan teks
        $cw=&$this->CurrentFont['cw'];
        if($w==0) $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);
        if($nb>0 and $s[$nb-1]=="\n") $nb--;
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $nl=1;
        while($i<$nb) {
            $c=$s[$i];
            if($c=="\n") {
                $i++; $sep=-1; $j=$i; $l=0; $nl++;
                continue;
            }
            if($c==' ') $sep=$i;
            $l+=$cw[$c];
            if($l>$wmax) {
                if($sep==-1) {
                    if($i==$j) $i++;
                } else $i=$sep+1;
                $sep=-1; $j=$i; $l=0; $nl++;
            } else $i++;
        }
        return $nl;
    }
}

// 4. Inisialisasi PDF dengan Orientasi LANDSCAPE ('L')
// A4 Landscape width = 297mm. Margin default 1cm (10mm) kiri kanan.
// Area kerja efektif = sktr 277mm.
$pdf = new PDF('L','mm','A4'); 
$pdf->username = $username;
$pdf->AliasNbPages();
$pdf->AddPage();

// 5. Setting Header Tabel
$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(52, 211, 153); // Warna Hijau (Sesuai tema Tailwind Emerald/Teal)
$pdf->SetTextColor(255); // Teks Putih untuk Header
$pdf->SetDrawColor(50, 50, 50); // Garis abu tua

// Definisi Lebar Kolom (Total harus <= 277mm)
$w_no = 15;
$w_nama = 70;     // Lebih lebar
$w_kat = 40;
$w_loc = 90;      // Lokasi butuh ruang banyak
$w_harga = 35;
$w_kunj = 35;

// Set Lebar Kolom ke Class agar bisa dibaca fungsi Row()
$pdf->SetWidths(array($w_no, $w_nama, $w_kat, $w_loc, $w_harga, $w_kunj));

// Set Perataan (C=Center, L=Left, R=Right) untuk Body
// No(C), Nama(L), Kat(L), Loc(L), Harga(R), Pengunjung(R)
$pdf->SetAligns(array('C', 'L', 'L', 'L', 'R', 'R'));

// Render Header Tabel
$pdf->Cell($w_no, 10, 'No', 1, 0, 'C', true);
$pdf->Cell($w_nama, 10, 'Nama Wisata', 1, 0, 'C', true);
$pdf->Cell($w_kat, 10, 'Kategori', 1, 0, 'C', true);
$pdf->Cell($w_loc, 10, 'Lokasi', 1, 0, 'C', true);
$pdf->Cell($w_harga, 10, 'Harga (Rp)', 1, 0, 'C', true);
$pdf->Cell($w_kunj, 10, 'Pengunjung', 1, 1, 'C', true); // Parameter terakhir 'true' untuk fill color

// 6. Reset Font & Warna untuk Isi Data
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(0); // Hitam
$pdf->SetFillColor(240, 240, 240); // Abu sangat muda untuk selang-seling (opsional)

$query = "SELECT w.*, k.nama_kategori 
          FROM wisata w 
          JOIN kategori k ON w.kategori_id = k.id 
          ORDER BY w.nama_wisata ASC";

$result = mysqli_query($conn, $query);

// Cek jika query error
if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}

$no = 1;
while($row = mysqli_fetch_assoc($result)){
    
    // Logika sederhana untuk 'Wrap' text jika terlalu panjang (menggunakan substr yang lebih panjang)
    // Catatan: FPDF murni sulit handle auto-wrap row height tanpa script tambahan.
    // Kita maksimalkan lebar kolom Landscape agar tidak perlu potong terlalu banyak.
    
    $nama = substr($row['nama_wisata'], 0, 35); // 35 chars cukup aman di kolom 70mm
    $lokasi = substr($row['lokasi'], 0, 45);    // 45 chars aman di kolom 80mm

    $pdf->Row(array(
        $no++,
        $row['nama_wisata'],
        $row['nama_kategori'],
        $row['lokasi'], // Data panjang akan otomatis turun baris
        number_format($row['harga_tiket'], 0, ',', '.'),
        number_format($row['total_pengunjung'], 0, ',', '.')
    ));
}

// 7. Output dengan Nama File
// 'I' = Inline (Tampil di browser), 'D' = Download paksa. Gunakan 'I' agar user bisa preview dulu.
$filename = "Laporan_Wisata_LombokTour_" . date('Ymd_His') . ".pdf";
$pdf->Output('I', $filename);
?>