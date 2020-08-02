<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Post;
use App\Form\PostType;
use App\Service\FileManagerServiceInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPostController extends AdminBaseController
{
    /**
     * @Route("/admin/post", name="admin_post")
     */
    public function index()
    {
        $post = $this->getDoctrine()->getRepository(Post::class)
            ->findAll();
        $checkCategory = $this->getDoctrine()->getRepository(Category::class)
            ->findAll();
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Postes';
        $forRender['post'] = $post;
        $forRender['check_category'] = $checkCategory;
        return $this->render('admin/post/index.html.twig', $forRender);
    }

    /**
     * @Route("/admin/post/create", name="admin_post_create")
     * @param Request $request
     * @param FileManagerServiceInterface $fileManagerService
     * @return RedirectResponse|Response
     */
    public function create(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $post->setCreateAtValue();
            $post->setUpdateAtValue();
            $post->setIsPublished();
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Publication réussie');
            return $this->redirectToRoute('admin_post');
        }
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Publication';
        $forRender['form'] = $form->createView();
        return $this->render('admin/post/form.html.twig', $forRender);
    }

    /**
     * @Route("/admin/post/update/{id}", name="admin_post_update")
     * @param int $id
     * @param Request $request
     * @param FileManagerServiceInterface $fileManagerService
     * @return RedirectResponse|Response
     */
    public function update(int $id, Request $request, FileManagerServiceInterface $fileManagerService)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $this->getDoctrine()->getRepository(Post::class)
            ->find($id);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            if ($form->get('save')->isClicked()){
                $image = $form->get('image')->getData();
                $imageOld = $post->getImage();
                if ($image){
                    if ($imageOld){
                        $fileManagerService->removePostImage($imageOld);
                    }
                    $fileName = $fileManagerService->imagePostUpload($image);
                    $post->setImage($fileName);
                }
                $post->setUpdateAtValue();
                $this->addFlash('success', 'Publication moddifiée');
            }
            if ($form->get('delete')->isClicked()){
                $image = $post->getImage();
                if ($image){
                    $fileManagerService->removePostImage($image);
                }
                $em->remove($post);
                $this->addFlash('success', 'Publication suprimée');
            }

            $em->flush();
            return $this->redirectToRoute('admin_post');
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Modification de la publication';
        $forRender['form'] = $form->createView();
        return $this->render('admin/post/form.html.twig', $forRender);
    }
}