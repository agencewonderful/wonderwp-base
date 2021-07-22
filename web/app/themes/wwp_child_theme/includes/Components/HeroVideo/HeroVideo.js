import {PewComponent} from "../../../assets/raw/js/components/pew-component";

export default class HeroVideo extends PewComponent {
  init() {
    this.registerVideoTriggers();
  }

  registerVideoTriggers() {
    const $sides = this.element.find('.hero-side');

    $sides.each((i,side)=>{
      let $side = $(side);
      $side.find('video')[0].pause();
      $side.hover(
        function () {
          $side.find('video')[0].play();
        },
        function () {
          $side.find('video')[0].pause();
        }
      );
    });
  }
}

window.pew.addRegistryEntry({key: 'hero', domSelector: '.section-hero-video', classDef: HeroVideo});
