<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class NewsController extends AbstractController
{
    private $maxCountInSubsequence = 500;
    /**
     * @Route("/", name="homepage")
     */
    public function index(Environment $twig, NewsRepository $newsRepository): Response
    {

        sleep(1);
            $this->truncateNews();
            $this->generateSubsequence();
        sleep(1);

        return new Response($twig->render('News/index.html.twig', [
              'news' => $newsRepository->findAll(),
        ]));
    }

    public function generateSubsequence () {

        for ($i = 1; $i <= $this->maxCountInSubsequence; $i++){
           // var_dump($i); echo '<br>';
            $this->createPost($i.' contains no data', $i.' contains no data', $i );
        }
    }

    public function createPost($title, $announce, $count){

        $entityManager = $this->getDoctrine()->getManager();

        $new = new News();
        $new->setTitle($title);
        $new->setText($announce);
        $date = new \DateTime('@'.strtotime('now'));
        $new->setPublishDate($date);
        $new->setStatusId($count?$count:1);

        // сообщите Doctrine, что вы хотите (в итоге) сохранить Продукт (пока без запросов)
        $entityManager->persist($new);

        // действительно выполните запросы (например, запрос INSERT)
        $entityManager->flush();

    }


    public function truncateNews(){
        $stmt = $this->getDoctrine()->getManager();
        $sql = 'DELETE FROM App\Entity\News';
        $stmt->createQuery($sql)->execute();
    }
}