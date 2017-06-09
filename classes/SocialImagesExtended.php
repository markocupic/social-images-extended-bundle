<?php
/**
 * Created by PhpStorm.
 * User: Marko
 * Date: 05.06.2017
 * Time: 20:02
 */

namespace Markocupic;


class SocialImagesExtended extends \Controller
{


    /**
     * Add the social images to the page
     *
     * @param \PageModel $objPage
     * @param \LayoutModel $objLayout
     */
    public function addSocialImages(\PageModel $objPage, \LayoutModel $objLayout)
    {
        if (!is_array($GLOBALS['SOCIAL_IMAGES']) || count($GLOBALS['SOCIAL_IMAGES']) < 1)
        {
            return;
        }

        $arrImages = array_unique($GLOBALS['SOCIAL_IMAGES']);

        // Limit the images
        if ($objLayout->socialImages_limit > 0)
        {
            $arrImages = array_slice($arrImages, 0, $objLayout->socialImages_limit);
        }

        $arrDimensions = deserialize($objLayout->socialImages_size, true);

        $countImages = 0;
        foreach ($arrImages as $strImage)
        {
            // Check the dimensions limit
            if ($arrDimensions[0] > 0 && $arrDimensions[1] > 0)
            {
                list($width, $height) = getimagesize(TL_ROOT . '/' . $strImage);

                if ($width < $arrDimensions[0] || $height < $arrDimensions[1])
                {
                    continue;
                }
            }
            $countImages++;

            if ($objPage->outputFormat == 'xhtml')
            {
                //$GLOBALS['TL_HEAD'][] = '<meta property="og:image" content="' . $this->generateImageUrl($strImage) . '" />';
                if ($GLOBALS['SOCIAL_DESCRIPTION'] != '')
                {
                    $GLOBALS['TL_HEAD'][] = '<meta property="og:description" content="' . $GLOBALS['SOCIAL_DESCRIPTION'] . '"/>';
                }
            }
            else
            {
                //$GLOBALS['TL_HEAD'][] = '<meta property="og:image" content="' . $this->generateImageUrl($strImage) . '">';
                if ($GLOBALS['SOCIAL_DESCRIPTION'] != '')
                {
                    $GLOBALS['TL_HEAD'][] = '<meta property="og:description" content="' . $GLOBALS['SOCIAL_DESCRIPTION'] . '">';
                }
            }
        }
    }

    /**
     * Collect the social image caption from page
     * @param object
     * @param object
     */
    public function getPageImageCaption($objPage, $objLayout)
    {
        if (!$objLayout->socialImages)
        {
            return;
        }


        // Add the current page image caption
        if ($objPage->socialImage && ($objImage = \FilesModel::findByUuid($objPage->socialImage)) !== null && is_file(TL_ROOT . '/' . $objImage->path))
        {
            $GLOBALS['SOCIAL_DESCRIPTION'] = $objPage->socialDescription;
        }
        // Walk the trail
        else
        {
            $objTrail = \PageModel::findParentsById($objPage->id);

            if ($objTrail !== null)
            {
                while ($objTrail->next())
                {
                    // Add the image
                    if ($objTrail->socialImage && ($objImage = \FilesModel::findByUuid($objTrail->socialImage)) !== null && is_file(TL_ROOT . '/' . $objImage->path))
                    {
                        if($objPage->socialDescription != '')
                        {
                            $GLOBALS['SOCIAL_DESCRIPTION'] = $objPage->socialDescription;
                        }
                        break;
                    }
                }
            }
        }
    }


    /**
     * Collect the images from news (parseArticles-Hook)
     * @param object
     * @param array
     */
    public function collectNewsImages($objTemplate, $arrData, $objModule)
    {
        if ($arrData['socialImage'])
        {
            $objFile = \FilesModel::findByPk($arrData['socialImage']);

            if ($objFile !== null && is_file(TL_ROOT . '/' . $objFile->path))
            {
                // Initialize the array
                if (!is_array($GLOBALS['SOCIAL_IMAGES']))
                {
                    $GLOBALS['SOCIAL_IMAGES'] = array();
                }
                array_unshift($GLOBALS['SOCIAL_IMAGES'], $objFile->path);
                if($arrData['socialDescription'] != '')
                {
                    $GLOBALS['SOCIAL_DESCRIPTION'] = $arrData['socialDescription'];
                }
            }
        }
    }
}