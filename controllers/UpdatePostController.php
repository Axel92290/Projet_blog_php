<?php
namespace Controllers;

use Models\Post;

class UpdatePostController extends BaseController
{
        /**
     * @var string
     */
    private array $errors = [];


    public function updatePost($id)
    {


        $detailPost = $this->getPost($id); ;

        $userId = $_SESSION['user']['id'];
        $userRole = $_SESSION['user']['role'];
        $postUserId = $detailPost[0]['id'];
        
        $this->verifRole($userRole, $userId, $postUserId);

        if(!empty($_POST)){
            if (empty($_POST['title'])) {
                $this->errors[] = 'Veuillez remplir le champ titre';
            } elseif (empty($_POST['content'])) {
                $this->errors[] = 'Veuillez remplir le champ contenu';
            }elseif (empty($_POST['chapo'])) {
                $this->errors[] = 'Veuillez remplir le champ chapo';
            }else {
              
                $titre = htmlspecialchars(ucfirst(trim($_POST['title']))); 
                $chapo = htmlspecialchars(ucfirst(trim($_POST['chapo'])));
                $contenu = htmlspecialchars(ucfirst(trim($_POST['content'])));
                $this->updatePostData($titre, $chapo, $contenu, $id);

                header("Location: /details-posts/$id");
                exit;
            }
        }



        // on choisi la template à appeler
        $template = $this->twig->load('update-post/update.html');


        // Puis on affiche la page avec la méthode render
        echo $template->render([
            'title' => 'Edition du post',
            'detailPost' => $detailPost[0],
            'errors' => $this->errors,


        ]);



    }


    private function verifRole($userRole, $userId, $postUserId)
    {
        if ($userRole === "admin" || $userId === $postUserId) {

        }else{
            header('Location: /error/');
            exit;}

    }

    private function getPost($id)
    {
        $post = new Post();
        $detailPost = $post->getPosts($id);
        return $detailPost;
    }


    private function updatePostData($titre, $chapo, $contenu, $id)
    {
        $post = new Post();
        $post->updatePost($titre, $chapo, $contenu, $id);
    }
}