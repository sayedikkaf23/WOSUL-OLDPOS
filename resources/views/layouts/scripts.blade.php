<script src="{{ asset('plugins/jquery/jquery-3.4.1.slim.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/popper.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/side_nav.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<script src="{{ asset('js/splide.min.js') }}"></script>
<script src="{{ asset('js/splide-extension-auto-scroll.min.js') }}"></script>

<script src="{{ mix('js/app.js') }}"></script>
<script type="text/javascript">
     function getPageTitle()
     {
        if(typeof(document.location.pathname.split("/")[1])!=="undefined")
        {
          let page_title = document.location.pathname.split("/")[1];
          if(page_title.includes("_")==true)
          {
           let page_title_array = page_title.split("_");
           let page_heading = "";
           for(let page_title in page_title_array)
           {
             page_heading+=page_title_array[page_title].charAt(0).toUpperCase()+page_title_array[page_title].substring(1)+" ";
           }
           if(page_heading!="")
           {
             document.title = `${page_heading} - `+document.title;
           }
          }
          else if(page_title.includes("-")==true)
          {
           let page_title_array = page_title.split("-");
           let page_heading = "";
           for(let page_title in page_title_array)
           {
             page_heading+=page_title_array[page_title].charAt(0).toUpperCase()+page_title_array[page_title].substring(1)+" ";
           }
           if(page_heading!="")
           {
             document.title = `${page_heading} - `+document.title;
           }
          }
          else
          {
           if(page_title!="")
           {
             document.title = `${page_title.charAt(0).toUpperCase()+page_title.substring(1)} - `+document.title;
           }
          }
        }
    }
getPageTitle();
</script>