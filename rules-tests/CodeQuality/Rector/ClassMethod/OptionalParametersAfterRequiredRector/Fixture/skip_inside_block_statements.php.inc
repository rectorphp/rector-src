<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

class SkipInsideBlockStatements
{
     private function handlePlugin(Request $request)
     {
        {
            $form = $this->createForm(ArticleFormType::class, $article);
            return $this->render('Article/edit.html.twig', [
                'media' => $media,
                'form' => $form->createView(),
                'headline' => $headline,
                'article' => $article
            ]);

            $form = $this->createForm(HeadlineType::class, $headline);
            return $this->render('headline/new.html.twig', [
                'headline' => $headline,
                'media' => $media,
                'form' => $form->createView(),
            ]);
        }
    }
}