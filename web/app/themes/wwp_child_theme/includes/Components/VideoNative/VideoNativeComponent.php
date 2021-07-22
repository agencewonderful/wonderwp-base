<?php
/**
 * Created by PhpStorm.
 * User: marclafay
 * Date: 20/03/2019
 * Time: 16:16
 */

namespace WonderWp\Theme\Child\Components\VideoNative;

use WonderWp\Theme\Core\Component\AbstractComponent;
use WonderWp\Plugin\GutenbergUtils\Bloc\Annotation\Block;
use WonderWp\Plugin\GutenbergUtils\Bloc\Annotation\BlockAttributes;

/**
 * @Block(title="Video native")
 *
 */
class VideoNativeComponent extends AbstractComponent
{
    /**
     * @var string
     * @BlockAttributes(component="PlainText",type="string",componentAttributes={"placeholder":"Titre de la vidéo"})
     */
    protected $title;

    /**
     * @var string
     * @BlockAttributes(component="MediaUpload",type="string")
     */
    protected $image;

    /**
     * @var string
     * @BlockAttributes(component="MediaUpload",type="string")
     */
    protected $videoMp4;

    /**
     * @var string
     * @BlockAttributes(component="MediaUpload",type="string")
     */
    protected $videoOgg;

    /**
     * @var string
     * @BlockAttributes(component="MediaUpload",type="string")
     */
    protected $videoWebm;

    /**
     * @var boolean
     */
    protected $hasControls = false;

    /**
     * @param string $title
     *
     * @return VideoNativeComponent
     */
    public function setTitle(string $title): VideoNativeComponent
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $videoMp4
     *
     * @return static
     */
    public function setVideoMp4(string $videoMp4)
    {
        $this->videoMp4 = $videoMp4;

        return $this;
    }

    /**
     * @param string $videoOgg
     *
     * @return static
     */
    public function setVideoOgg(string $videoOgg)
    {
        $this->videoOgg = $videoOgg;

        return $this;
    }

    /**
     * @param string $videoWebm
     *
     * @return static
     */
    public function setVideoWebm(string $videoWebm)
    {
        $this->videoWebm = $videoWebm;

        return $this;
    }

    /**
     * @param string $image
     *
     * @return VideoNativeComponent
     */
    public function setImage(string $image): VideoNativeComponent
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @param bool $hasControls
     * @return VideoNativeComponent
     */
    public function setHasControls(bool $hasControls): VideoNativeComponent
    {
        $this->hasControls = $hasControls;
        return $this;
    }

    public function getMarkup(array $opts = [])
    {
        if (strpos($this->image, '<img') !== false) {
            preg_match("/\<img.+src\=(?:\"|\')(.+?)(?:\"|\')(?:.+?)\>/", $this->image, $matches);
            $this->image = $matches[1];
        }
        $markup = '
        <div class="video-wrapper">
            <div class="video-player-wrap">
                <div class="video-player">
                   <video playsinline loop autoplay muted class="video" width="780" height="440" ' . (!empty($this->image) ? 'poster="' . $this->image . '"' : '') . ' data-object-fit="cover">';
        if (!empty($this->videoMp4)) {
            $markup .= '<source src="' . $this->videoMp4 . '" type="video/mp4">';
        }
        if (!empty($this->videoOgg)) {
            $markup .= '<source src="' . $this->videoOgg . '" type="video/ogg">';
        }
        if (!empty($this->videoWebm)) {
            $markup .= '<source src="' . $this->videoWebm . '" type="video/webm">';
        }
        $markup .= '
                        ' . trad('no_video_support.trad', WWP_THEME_TEXTDOMAIN) . '
                    </video>';

        if ($this->hasControls) {

            $markup .= '<button class="mute" type="button">Mute/Unmute</button>
                    <div id="video-controls" class="controls" data-state="visible">
                       <div class="progress-wrap">
                          <progress class="progress" value="0" max="100">
                             <span class="progress-bar"></span>
                          </progress>
                       </div>
                       <div class="left-buttons">
                            <button class="playpause" type="button" data-state="play">Play/Pause</button>
                        </div>
                        <div class="right-buttons">
                            <button class="fs" type="button" data-state="go-fullscreen">Fullscreen</button>
                        </div>
                       <button id="stop" type="button" data-state="stop">Stop</button>
                       <button id="volinc" type="button" data-state="volup">Vol+</button>
                       <button id="voldec" type="button" data-state="voldown">Vol-</button>
                    </div>';

        }

        $markup .= '</div>'; /*.video-player*/
        $markup .= '</div>'; /*.video-player-wrap*/

        $markup .= '</div>'; /*.video-wrapper*/

        return $markup;
    }

}
