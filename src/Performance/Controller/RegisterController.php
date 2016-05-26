<?php

namespace Performance\Controller;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Config;
use League\Flysystem\Filesystem;
use Performance\Domain\UseCase\SignUp;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegisterController
{
    /**
     * @var \Twig_Environment
     */
    private $template;

    /**
     * @var UrlGeneratorInterface
     */
    private $url_generator;

    /**
     * @var SignUp
     */
    private $useCase;

    public function __construct(\Twig_Environment $templating, UrlGeneratorInterface $url_generator, SignUp $useCase)
    {
        $this->template = $templating;
        $this->url_generator = $url_generator;
        $this->useCase = $useCase;
    }

    public function get()
    {
        return new Response($this->template->render('register.twig'));
    }

    public function post(Request $request)
    {
        include __DIR__ . '/../../../resources/config/aws_credentials.php';

        $username = $request->request->get('username');
        $password = $request->request->get('password');
        $image = $request->files->get('image');

        $client = new S3Client([
            'credentials' => [
                'key' => $key,
                'secret' => $secret,
            ],
            'region' => 'eu-west-1',
            'version' => 'latest',
        ]);

        $aws3adapter = new AwsS3Adapter($client, 'performancebcket');
        $filesystem = new Filesystem($aws3adapter, new Config([]));

        $finalImageName = $this->renameImage($username);
        $image->move(__DIR__ . '/../../../tmp', $finalImageName);

        $filesystem->write('/uploads/profile/' . $finalImageName, file_get_contents(__DIR__ . '/../../../tmp/' . $finalImageName));

        $this->useCase->execute($username, $password, $finalImageName);

        return new RedirectResponse($this->url_generator->generate('login'));
    }

    private function renameImage($username)
    {
        $salt = uniqid(mt_rand(), true);
        return md5($salt . $username) . '.png';
    }
}
