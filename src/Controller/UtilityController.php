<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\InstructorCourse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UtilityController extends AbstractController
{
    public static function getMessage($code)
    {
        $message = '';
        switch ($code) {
            case '1451':
                # code...
                $message = 'Please try to delete items related to this record first.';
                break;

            default:
                # code...
                break;
        }

        return $message;
    }

    /**
    * @Route("/getsummary/{id}", name="getsummary", methods={"GET"})
    */
    public static function getSummary(InstructorCourse $instructorCourse, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $result = array();
            $arr = array();
            $all = $instructorCourse->getInstructorCourseChapters();
            foreach ($all as  $chapter) {
                $contents =  $chapter->getContents();
                $cnt = array('video'=>0, 'youtube'=>0,'others'=>0);
                foreach ($contents as  $content) {
                    if ($content->getFileName()) {
                        $cnt['video'] ++;
                    } elseif ($content->getVideoLink()) {
                        $cnt['youtube'] ++;
                    } else {
                        $cnt['others'] ++;
                    }
                }

                $temp = array(
                    'chapter'=> $chapter->getTopic(),
                    'quiz'=> $chapter->getQuizzes() ? "YES" : "NO",
                    'contents'=> $cnt
            );
                $arr[]=$temp;
            }

            // dd( $arr);
            $returnResponse = new JsonResponse();
            $returnResponse->setJson(json_encode($arr));

            return $returnResponse;
        } else {
            die("error");
        }
    }
}
