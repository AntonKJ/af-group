<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use \DOMDocument;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class NewsController extends AbstractController
{
    private $maxCountInSubsequence = 500;
    private $htmlTagsCounts = [];
    /**
     * @Route("/", name="homepage")
     */
    public function index(Environment $twig, NewsRepository $newsRepository): Response
    {

        //echo '<pre>';
        //var_dump($this->parseNewsPage()); exit();
        $newsParse = $this->parseNewsPage();

        sleep(1);
            $this->truncateNews();
            $this->generateSubsequence($newsParse);
        sleep(1);

        return new Response($twig->render('News/index.html.twig', [
              'news' => $newsRepository->findAll(),
              'tags' => $this->htmlTagsCounts
        ]));
    }

    public function parseNewsPage () {
        $content = $this->getNewsPage();
        preg_match_all('|uho__link\s*uho__link--overlay">\s*(.*)<\/a>|iu', $content['news_response'], $titles);
        preg_match_all('|uho__subtitle\s*rubric_lenta__item_subtitle">\s*(.*)\s*<\/h3>|iu', $content['news_response'], $annotations);
        preg_match_all('|<img class=" fallback_image\s*"(.*\s*alt="(.*)")|iu', $content['news_response'], $images);

        return [
          'titles' => $titles,
          'annotations' => $annotations,
          'images' => $images
        ];
    }

    public function getNewsPage($data = '', $command = '', $toLog = '') {
        $url = 'https://www.kommersant.ru/autopilot?from=burger' . $command;

        $postdata = $data;

        /************ Отправка с использованием cUrl ************/
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //Автоматический редирект

        $curlResponse = curl_exec($ch);
        //var_dump($curlResponse); exit();
        $this->htmlTagsCounts = $this->htmlElementCount($curlResponse);
        $info = curl_getinfo($ch);
        $responseCURL['curl_info'] = $info;
        if (empty($curlResponse)) {
            $responseCURL['curl_error'] = curl_error($ch);
            $responseCURL['curl_info_plain'] = $info;
        }

        $responseCURL['news_response'] = $curlResponse;
        curl_close($ch);

        return $responseCURL;
    }

    public function generateSubsequence ($parses) {
        for ($i = 1; $i <= $this->maxCountInSubsequence; $i++){

            if (isset($parses['titles'][1][$i-1])){
                $image = '';
                foreach ($parses['images'][2] as $key => $alt){
                    if ((string)$alt === $parses['titles'][1][$i-1]){
                        if (isset($parses['images'][1][$key])) {
                            $image = $parses['images'][1][$key];
                        }
                    }
                }
                $this->createPost($parses['titles'][1][$i-1],
                    (isset($parses['annotations'][1][$i-1])?str_replace('/doc/', 'https://www.kommersant.ru/doc/' ,$parses['annotations'][1][$i-1]):$i . ' contains no data')
                    .( $image != ''?'<br/><img '.$image.'">':''),
                    $i);
                $image = '';
            } else {
                $this->createPost($i . ' contains no data', $i . ' contains no data', $i);
            }
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

    public function htmlElementCount ($HTML){
        $dom = new DOMDocument;
        @$dom->loadHTML($HTML);
        $allElements = $dom->getElementsByTagName('*');
        //echo $allElements->length;

        $elementDistribution = array();
        foreach($allElements as $element) {
            if(array_key_exists($element->tagName, $elementDistribution)) {
                $elementDistribution[$element->tagName]['name'] = $element->tagName;
                $elementDistribution[$element->tagName]['val'] += 1;
            } else {
                $elementDistribution[$element->tagName]['name'] = $element->tagName;
                $elementDistribution[$element->tagName]['val'] = 1;
            }
        }

        return $elementDistribution;
    }
}