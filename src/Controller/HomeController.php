<?php

namespace App\Controller;

use App\Repository\InstructorCourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ottosmops\Pdftotext\Extract;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Config\FrameworkConfig;
use CodeItNow\BarcodeBundle\Utils\QrCode;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(InstructorCourseRepository $course)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $courses = $course->findCoursesSortByCategory();
            return $this->render('course/couses_list.html.twig', [
                'courses' => $courses,
            ]);
            //return $this->redirectToRoute("courses_list");
        }

        if ($this->isGranted('ROLE_STUDENT')) {
            return $this->redirectToRoute("student_course_index");
        } else {
            return $this->render('home/admin_index.html.twig', [

            ]);
        }
    }

    /**
     * @Route("/qrcode", name="qrcode")
     */
    public function qrCode()
    {

        $qrCode = new QrCode();
        $qrCode
        ->setText('Nebiye Lioul Temesgen')
        ->setSize(80)
        ->setPadding(10)
        ->setErrorCorrection('high')
        ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
        ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
        ->setLabel('Scan Qr Code')
        ->setLabelFontSize(12)
        ->setImageType(QrCode::IMAGE_TYPE_PNG);
        echo '<img src="data:'.$qrCode->getContentType().';base64,'.$qrCode->generate().'" />';
    }


        /**
     * @Route("/upload", name="upload", methods={"POST"})
     */
    public function upload(Request $request)
    {

        $data = file_get_contents('php://input');
        // $fp = fopen("/text.mp4", "wb");

        // dd($_FILES, $_POST);
        if($_FILES['data']){
            $my_file = $_FILES['data'];
            $my_blob = file_get_contents($my_file['tmp_name']);
            file_put_contents('answerVideos/test1.webm', $my_blob);
        }
        // fwrite($fp, $data);
        // fclose($fp);
        //  return $this->render('dem/index.html.twig');
        // return $this->render('academic_level/recorder.html.twig', ['data' => $data]);
    }

    /**
     * @Route("/upl")
     */
    public function upl(Request $request)
    {
        $data = "";
        return $this->render('academic_level/recorder.html.twig', ['data' => $data]);
    }

    /**
     * @Route("/excel", name="excel")
     */
    public function excel(){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $writer->setPreCalculateFormulas(false);
        $sheet->setCellValue('A1', 'Hello World !');

        $writer = new Xlsx($spreadsheet);
        $writer->save('hello world.xlsx');



        $spreadsheet = new Spreadsheet();
$Excel_writer = new Xlsx($spreadsheet);
  
$spreadsheet->setActiveSheetIndex(0);
$activeSheet = $spreadsheet->getActiveSheet();
  
$activeSheet->setCellValue('A1', 'Product Name');
$activeSheet->setCellValue('B1', 'Product SKU');
$activeSheet->setCellValue('C1', 'Product Price');
  
$query = $db->query("SELECT * FROM products");
  
if($query->num_rows > 0) {
    $i = 2;
    while($row = $query->fetch_assoc()) {
        $activeSheet->setCellValue('A'.$i , $row['product_name']);
        $activeSheet->setCellValue('B'.$i , $row['product_sku']);
        $activeSheet->setCellValue('C'.$i , $row['product_price']);
        $i++;
    }
}
  
$filename = 'products.xlsx';
  
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='. $filename);
header('Cache-Control: max-age=0');
$Excel_writer->save('php://output');
        dd("");
    }
    /**
     * @Route("/print", name="print")
     */
    public function print()
    {
        return $this->render("certificate/print.html.twig");
    }
     
}
