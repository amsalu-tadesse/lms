<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\InstructorCourseRepository;
use Ottosmops\Pdftotext\Extract;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Config\FrameworkConfig;
use CodeItNow\BarcodeBundle\Utils\QrCode;



class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(InstructorCourseRepository $course)
    {
        if(!$this->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $courses = $course->findCoursesSortByCategory();
            return $this->render('course/couses_list.html.twig',[
                'courses' => $courses
            ]);
            //return $this->redirectToRoute("courses_list");
        }
      
        if($this->isGranted('ROLE_STUDENT'))
        {
            return $this->redirectToRoute("student_course_index");
        }
        else
        {
            
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
     * @Route("/print", name="print")
     */
    public function print()
    {
        return $this->render("certificate/print.html.twig");
    }
      /**
     * @Route("/pdf", name="test")
     */
        public function temp()
        {
           /* $projectRoot = $this->getParameter('docroot');
            $dotenv = new Dotenv();
            $dd = $dotenv->load($projectRoot.'/.env');
            //  $_ENV['DATABASE_URL'] = "hahahaha";
             $dbUser =  $dd->getenv('DATABASE_URL');
             
           dd($dbUser);*/

          /* $dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');
$dbUser = getenv('DB_USER');
dd($dbUser);

dd("done");*/

// $c = $_ENV['DATABASE_URL'];
// $v = $this->params->get('DATABASE_URL');
// $container->setParameter('DATABASE_URL', '88888888888');
// $container = $this->get('validator.email');
// $object = $container->get('foo.baz.bar');
// dd($container);

 
        }

 
}
