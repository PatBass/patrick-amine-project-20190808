
  // Collapse Extension
  // ===============================

     var $searchPanel = $('#search')
     //$searchPanel.addClass('search-collapsing')

     var $colltabs =  $('[data-toggle="collapse"]')

     $colltabs.click(function (e) {
        e.preventDefault();
     });

      $colltabs.attr({ 'aria-expanded':'false', 'role':"button" })
      $colltabs.each(function( index ) {
        var colltab = $(this)
        , collpanel = (colltab.attr('data-target')) ? $(colltab.attr('data-target')) : $(colltab.attr('href'))
        , parent  = colltab.attr('data-parent')
        , collparent = parent && $(parent)
        , collid = colltab.attr('id') || uniqueId('ui-collapse')

        $(collparent).find('div:not(.collapse,.panel-body), h4').attr('role','presentation')

          colltab.attr('id', collid)

//        if(collparent){
//          collparent.attr({ 'aria-multiselectable' : 'true' })
//        }

          var ariaControls = colltab.attr('data-target').substr(1) || colltab.attr('href').substr(1)
          if(collpanel.hasClass('in')){
            colltab.attr({ 'aria-controls': ariaControls, 'aria-expanded': 'true' }) //, 'tabindex':'0'
            collpanel.attr({ 'aria-labelledby': collid, 'aria-hidden': 'false' }) // 'tabindex':'0',
          }else{
            colltab.attr({'aria-controls': ariaControls }) //, 'tabindex':'0'
            collpanel.attr({ 'aria-labelledby': collid, 'aria-hidden':'true' }) // 'tabindex':'-1',
          }

      })
/*
    var collShow = $.fn.collapse.Constructor.prototype.show
    $.fn.collapse.Constructor.prototype.show = function(){

      if (this.$parent && $(".banner-outer")) {

        var parentNav = $(".banner-outer")
        , curPanel = this.$element[0]

        parentNav
          .one($.support.transition.end, function(){
            this.transitioning = 0
            $(this).css("margin-bottom", curPanel.offsetHeight)
          })
          .emulateTransitionEnd(350)
          .css("margin-bottom", curPanel.scrollHeight)

      }

      collShow.apply(this, arguments)
    }

    var collHide = $.fn.collapse.Constructor.prototype.hide
    $.fn.collapse.Constructor.prototype.hide = function(){

      if (this.$parent && $(".banner-outer")) {

        var parentNav = $(".banner-outer")

        parentNav
          .css("margin-bottom", 0)
          .one($.support.transition.end, function(){
            this.transitioning = 0
          })
          .emulateTransitionEnd(350)

      }

      collHide.apply(this, arguments)
    }
*/
    var collToggle = $.fn.collapse.Constructor.prototype.toggle
    $.fn.collapse.Constructor.prototype.toggle = function(){
      var prevTab = this.$parent && this.$parent.find('[aria-expanded="true"]') , href

      if(prevTab){
        var prevPanel = prevTab.attr('data-target') || (href = prevTab.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '')
        , $prevPanel = $(prevPanel)
        , $curPanel = this.$element
        , par = this.$parent
        , curTab

        if (this.$parent) curTab = this.$parent.find('[data-toggle=collapse][data-target="#' + this.$element.attr('id') + '"]') || this.$parent.find('[data-toggle=collapse][href="#' + this.$element.attr('id') + '"]')

        collToggle.apply(this, arguments)

        if ($.support.transition) {
          this.$element.one($.support.transition.end, function(){

              if (curTab.hasClass('nav-main-item')) {
                //$searchPanel.toggleClass('search-collapsing')
                $searchPanel.css('padding-top', Math.floor($curPanel.height()) + 'px')
                //$('#nav-main').css('margin-bottom', Math.floor($curPanel.height()) + 'px')
              }

              prevTab.attr({ 'aria-expanded': 'false' }) // , 'tabIndex':'-1'
              $prevPanel.attr({ 'aria-hidden': 'true' }) // ,'tabIndex' : '-1'

              curTab.attr({ 'aria-expanded':'true' }) // , 'tabIndex':'0'

              if($curPanel.hasClass('in')){
                $curPanel.attr('aria-hidden', 'false');
              }else{
                curTab.attr({ 'aria-expanded':'false'})
                $curPanel.attr({ 'aria-hidden' : 'true'})
              }

          })

        }
      }else{
        var $curPanel = this.$element
        , curTab

        curTab = $('[data-toggle=collapse][data-target="#' + $curPanel.attr('id') + '"]') || $('[data-toggle=collapse][href="#' + $curPanel.attr('id') + '"]')

        collToggle.apply(this, arguments)

        if ($.support.transition) {
          this.$element.one($.support.transition.end, function(){

              curTab.attr({ 'aria-expanded': 'true' }) // , 'tabIndex':'0'

              if($curPanel.hasClass('in')){
                $curPanel.attr('aria-hidden', 'false');
              }else{
                curTab.attr({ 'aria-expanded':'false'})
                $curPanel.attr({ 'aria-hidden' : 'true'})
              }

              if (curTab.hasClass('nav-main-item')) {
                //$searchPanel.toggleClass('search-collapsing')
                $searchPanel.css('padding-top', Math.floor($curPanel.height()) + 'px')
                //$('#nav-main').css('margin-bottom', Math.floor($curPanel.height()) + 'px')

              }

          })
        }
      }



    }

    $.fn.collapse.Constructor.prototype.keydown = function (e) {
      /*var $this = $(this)
      , $items
      , $tablist = $this.closest('div[role=tablist] ')
      , index
      , k = e.which || e.keyCode

      $this = $(this)
      if (!/(32|37|38|39|40)/.test(k)) return
      if(k==32) $this.click()

      $items = $tablist.find('[role=tab]')
      index = $items.index($items.filter(':focus'))

      if (k == 38 || k == 37) index--                                        // up & left
      if (k == 39 || k == 40) index++                        // down & right
      if(index < 0) index = $items.length -1
      if(index == $items.length) index = 0

      $items.eq(index).focus()

      e.preventDefault()
      e.stopPropagation()*/

    }

    $(document).on('keydown.collapse.data-api','[data-toggle="collapse"]' ,  $.fn.collapse.Constructor.prototype.keydown)




