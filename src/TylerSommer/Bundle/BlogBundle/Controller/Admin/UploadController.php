<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace TylerSommer\Bundle\BlogBundle\Controller\Admin;

use Orkestra\Bundle\ApplicationBundle\Controller\Controller;
use Orkestra\Bundle\ApplicationBundle\Entity\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Upload controller
 *
 * @Route("/upload")
 */
class UploadController extends Controller
{
    const CKEDITOR_UPLOAD_DIR = '/files/ckeditor';
    const FILE_SIZE_MAX = 102400;

    /**
     * Handles ckeditor uploads
     *
     * @Route("/ckeditor", name="ckeditor_upload", defaults={"_format"="json"})
     * @Secure(roles="ROLE_ADMIN")
     */
    public function ckeditorAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $funcNumber = $request->get('CKEditorFuncNum');

        if ($file = $request->files->get('upload')) {
            $supportedTypes = array(
                'image/jpeg',
                'image/png'
            );

            if (!in_array($file->getMimeType(), $supportedTypes)) {
                return $this->getCkeditorResponse('The given file type is not supported', '', $funcNumber);

            } elseif ($file->getSize() > static::FILE_SIZE_MAX) {
                return $this->getCkeditorResponse(
                    'The given file is too large. Please limit the file to under 100kb.',
                    '',
                    $funcNumber
                );
            }

            try {
                $uploadDir = $this->get('kernel')->getRootDir() . static::CKEDITOR_UPLOAD_DIR;
                $file      = File::createFromUploadedFile($file, $uploadDir);
                $file->moveUploadedFile();

                $em->persist($file);
                $em->flush();
            } catch (\Exception $e) {
                return $this->getCkeditorResponse($e->getMessage(), '', $funcNumber);
            }
        }

        if (!($file && file_exists($file->getPath()))) {
            return $this->getCkeditorResponse('There was an error while uploading the file. The file was not copied to the upload directory', '', $funcNumber);
        }

        $src = $this->generateUrl('view_file', array('id' => $file->getId()), true);

        return $this->getCkeditorResponse('Upload Successful', $src, $funcNumber);
    }

    private function getCkeditorResponse($msg, $src, $funcNumber)
    {
       return new Response(
           '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('.$funcNumber.', "'.addslashes($src).'", "'.addslashes($msg).'");</script>',
           200,
           array('Content-Type' => 'text/html'));
    }
}
