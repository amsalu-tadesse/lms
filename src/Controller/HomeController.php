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
use App\Repository\StudentCourseRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(InstructorCourseRepository $course)
    {
        /*$count = 1;
        $html='';
        for ($i=50002; $i<=50025; $i++)
        {
            $html .='
            <br>
        ############################# SITE '.$count.' #################################### <br>
        &lt;VirtualHost *:'.$i.'&gt;<br>
        ServerName htmlsite<br>
        ServerAlias www/html/site'.$count.'<br>
        #Redirect permanent / https://html/site'.$count.'<br>
        ServerAdmin info@html/site'.$count.'<br>
        DocumentRoot /var/www/html/site'.$count.'<br>
        &lt;Directory "/var/www/html/site'.$count.'"&gt;<br>
        Options FollowSymLinks<br>
        AllowOverride All<br>
        Order allow,deny<br>
        Allow from all<br>
        SetEnvIf Remote_Addr "127.0.0.1" dontlog<br>
        SetEnvIf Remote_Addr "::1" dontlog<br>
        RewriteEngine on<br>
        RewriteBase /<br>
        RewriteCond %{REQUEST_FILENAME} !-f<br>
        RewriteCond %{REQUEST_FILENAME} !-d<br>
        RewriteRule ^(.*)$ index.php?q=$1 [L,QSA]<br>
        RewriteCond %{SERVER_NAME} =site'.$count.'/site'.$count.' [OR]<br>
        RewriteCond %{SERVER_NAME} =site'.$count.'<br>
        RewriteRule ^ https://%{SERVER_NAME}%{REQUEST_URI} [END,NE,R=permanent] <br>
        &lt;/Directory&gt;<br>
        ErrorLog ${APACHE_LOG_DIR}/site'.$count.'_error.log <br>
        CustomLog ${APACHE_LOG_DIR}/site'.$count.'_access.log combined env=!dontlog <br>
        &lt;/VirtualHost&gt;
       <br>
       
       <br>';
       $count ++;

        }
        echo $html;
        dd($html);*/


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
     * @Route("/certificate/{student}/{id}", name="v", requirements={"id":"\d{1,6}", "student":"[A-Z]{2}\-[\d]{3,6}\-[\d]{2}"})
    */
    public function certificate($student, $id, StudentCourseRepository $stud_course_repo)
    {
        $student1 = $stud_course_repo->getStudentCertificate($student, $id);
        return $this->render('certificate/show.html.twig',[
            'student_course' => $student1,
        ]);
        //
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
