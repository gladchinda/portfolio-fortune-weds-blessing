<?php

namespace App\Http\Controllers\Rest;

use Storage;
use Exception;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use PHPThumb\GD as Thumbnailer;
use App\Http\Controllers\Controller;
use App\Enhance\File\Storage\FileStorage;
use App\Http\Controllers\Core\Support\UploadsPhoto;

class MainController extends Controller
{
	use UploadsPhoto;

	public function getCoupleData()
    {
        $couple = [
            'groom' => [
                'name' => 'Fortune Chinda',
                'who' => 'groom',
                'avatar' => 'https://s3.eu-west-2.amazonaws.com/fortunewedsblessing.com/images/static/1492982107_a6c11eab39f6e2bfba1860b364854ee3.jpeg',
                'social' => [
                    'facebook' => 'https://web.facebook.com/gerachblings',
                    'twitter' => 'https://twitter.com/gerachblings',
                    'instagram' => 'https://www.instagram.com/gerachblings/',
                ],
                'profile' => '<strong>Fortune Chinda</strong>, also known as <strong>(Phortunez)</strong>, is a young gentleman from Rivers State, Nigeria. He is an artist, author, idea and brand expert, design engineer and planner, dynamic songwriter, music instructor, and teens coach. He is the <strong>CEO/Creative Director</strong> of Innovation House Company. He is also a <strong>Team Leader</strong> at Mould The Young Foundation, and a <strong>Recording Artist</strong> at Phortunez &amp; GospelMix Family.',
            ],
            'bride' => [
                'name' => 'Blessing Peter',
                'who' => 'bride',
                'avatar' => 'https://s3.eu-west-2.amazonaws.com/fortunewedsblessing.com/images/static/1492981958_98313490131c5af5f1558a5c627ca1f3.jpeg',
                'social' => [
                    'facebook' => 'https://web.facebook.com/blessing.peter.56829',
                    'twitter' => 'https://twitter.com/peterzblessing',
                    'instagram' => 'https://www.instagram.com/blizzbeautified/',
                ],
                'profile' => '<strong>Blessing Peter</strong> is a young lady with a great personality. She hails from Edo State, Nigeria. She is passionate about inspiring the younger ones; keeping them in perspective and moving their lives in a positive direction. She is a biochemist, strategist, professional hair stylist, make-up artist, young people\'s coach, and amazing writer. She is the <strong>Creative Director</strong> of Blizz Beautified Hair Styling, Braiding &amp; Make-Up Finery, and also a <strong>Project Assistant</strong> at Innovation House Company &amp; Mould The Young Foundation.',
            ],
        ];

        return $couple;
    }

    public function getEventsData()
    {
        $events = [
            'traditional' => [
                'label' => 'Traditional Marriage',
                'code2' => 'tm',
                'date' => '2017-04-18',
                'colors' => ['wine', 'cream'],
            ],
            'wedding' => [
                'label' => 'White Wedding',
                'code2' => 'wd',
                'date' => '2017-04-29',
                'colors' => ['teal', 'peach'],
            ],
        ];

        return $events;
    }

    public function getPhotosData()
    {
        $disk = Storage::disk('s3');

        $files = array_map(function($file) {
            return substr(strrchr($file, '/'), 1);
        }, $disk->files('images/uploads'));

        return ['photos' => $files];
    }

    public function getLocationsData()
    {
        $locations = [
            [
                'event' => 'Traditional Marriage',
                'place' => 'No. 5 Idumoza Street, Iruekpen',
                'address' => 'Ekpoma, Edo State',
                'mapUrl' => "https://goo.gl/maps/4Qjp4pCtePR2",
                'mapEmbedUrl' => 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15848.217429451253!2d6.0405583!3d6.763227!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1046eb22fd98ec41%3A0xcd1e34aec0816ad7!2sIruekpen%2C+Ekpoma!5e0!3m2!1sen!2sng!4v1492890211758',
            ],
            [
                'event' => 'White Wedding',
                'place' => 'RCCG, Glory House Parish, Rivers Province 3, PHC',
                'address' => 'Plot 8, Okworo, Standard Road, Elelenwo, Port Harcourt',
                'mapUrl' => "https://goo.gl/maps/5Anwhp2xL1v",
                'mapEmbedUrl' => 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15902.42288885766!2d7.0615335!3d4.837565!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xb572899d6dbd43e!2sThe+Redeemed+Christian+Church+of+God!5e0!3m2!1sen!2sng!4v1492890677855',
            ],
            [
                'event' => 'Wedding Reception',
                'place' => 'PN Events Place (former Pend Motels)',
                'address' => 'No. 54 - 56 Old Refinery Road, Elelenwo, PHC',
                'mapUrl' => "https://goo.gl/maps/dpR7zr3c1DU2",
                'mapEmbedUrl' => 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15902.235171059207!2d7.056174!3d4.8455499!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x171e62b6a581943!2sPN+Events+Place!5e0!3m2!1sen!2s!4v1492891142845',
            ],
        ];

        return $locations;
    }

    public function uploadPhoto(Request $request)
    {
        $photo = $this->uploadPhotoFromRequest($request, 'images/uploads', 'photo_file');
        $thumbUrl = $this->uploadThumbnailFromPhoto($photo);

        if (! $thumbUrl) {
            $photo->rollback();
            throw new Exception('Problem uploading photo.');
        }

        return [
            'success' => true,
            'url' => $photo->getStorageUrl(),
            'thumbUrl' => $thumbUrl,
            'filename' => $photo->getStorageFilename(),
        ];
    }

    protected function uploadThumbnailFromPhoto(FileStorage $photo)
    {
        if ($photo->isUploaded()) {

            $thumb = new Thumbnailer($photo->getStorageUrl());
            $imgdata = $thumb->adaptiveResize(75, 75)->getImageAsString();

            $disk = Storage::disk('s3');
            $thumbFile = 'images/uploads/thumbnails/' . $photo->getStorageFilename();

            if ($disk->put($thumbFile, $imgdata)) {
                return $disk->url($thumbFile);
            }

        }
    }

}
