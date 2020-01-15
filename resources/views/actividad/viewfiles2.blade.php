@foreach ($documentos as $documt)
  @if($documt->carpetauid==$conte->valorcarpeta)
	@if (($documt->extension=="png") or ($documt->extension=="jpg") or ($documt->extension=="gif"))
        <li>
            <img src="{{$destinationPath}}/{{$documt->thumbnails}}" >
        </li>
	@elseif (($documt->extension=="doc") or ($documt->extension=="docx"))
		<li>
          <span class="mailbox-attachment-icon">
                <!-- <a href="{{$destinationPath}}/{{$documt->nombrefisico}}" target="_blank" ></a>-->
                <i class="fa fa-file-word-o"></i>
            </span>

          <div class="mailbox-attachment-info no-print">
            <a href="#" class="mailbox-attachment-name" target="_blank" >{{$documt->nombre}}</a>
            <span class="mailbox-attachment-size">
                <a href="#" class="pull-right"><i class="fa fa-cloud-download"></i></a>
            </span>
     	</div>
        </li>
	@elseif (($documt->extension=="xls") or ($documt->extension=="xlsx") or ($documt->extension=="cvs"))
		<li>
          <span class="mailbox-attachment-icon">
            <!--  <a href="{{$destinationPath}}/{{$documt->nombrefisico}}" target="_blank" ></a>-->
              <i class="fa fa-file-word-o"></i></span>
          <div class="mailbox-attachment-info no-print">
            <a href="#" class="mailbox-attachment-name" target="_blank" >{{$documt->nombre}}</a>
            <span class="mailbox-attachment-size">
                <a href="#" class="pull-right"><i class="fa fa-cloud-download"></i></a>
            </span>
     	</div>
        </li>
	@elseif (($documt->extension=="mp3") or ($documt->extension=="ogg"))
		<li>
          <span class="mailbox-attachment-icon"><a href="#" target="_blank" ><i class="fa fa-file-audio-o"></i></a></span>

          <div class="mailbox-attachment-info no-print">
            <a href="{{$destinationPath}}/{{$documt->nombrefisico}}" target="_blank" class="mailbox-attachment-name">{{$documt->nombre}}</a>
            <span class="mailbox-attachment-size">
                <a href="#" class="pull-right"><i class="fa fa-cloud-download"></i></a>
            </span>
     	</div>
        </li>
	@elseif (($documt->extension=="avi") or ($documt->extension=="mpg") or ($documt->extension=="mpeg") or ($documt->extension=="mp4"))
		<li>
          <span class="mailbox-attachment-icon"><a href="#" target="_blank" ><i class="fa fa-file-movie-o"></i></a></span>

          <div class="mailbox-attachment-info no-print">
            <a href="#" target="_blank" class="mailbox-attachment-name no-print">{{$documt->nombre}}</a>
            <span class="mailbox-attachment-size no-print">
                <a href="#" class="pull-right"><i class="fa fa-cloud-download"></i></a>
            </span>
     	</div>
        </li>
    @elseif ($documt->extension=="pdf")
		<li>
          <span class="mailbox-attachment-icon">
            <!--  <a href="{{$destinationPath}}/{{$documt->nombrefisico}}" target="_blank" ></a>-->
            <i class="fa fa-file-pdf-o"></i></span>

          <div class="mailbox-attachment-info no-print">
            <a href="#" target="_blank" class="mailbox-attachment-name">{{$documt->nombre}}</a>
            <span class="mailbox-attachment-size">
                <a href="#" class="pull-right"><i class="fa fa-cloud-download"></i></a>
            </span>
     	</div>
        </li>
    @elseif ($documt->extension=="txt")
		<li>
          <span class="mailbox-attachment-icon"><a href="#" target="_blank" ><i class="fa fa-file-text-o"></i></a></span>

          <div class="mailbox-attachment-info no-print">
            <a href="#"target="_blank" class="mailbox-attachment-name">{{$documt->nombre}}</a>
            <span class="mailbox-attachment-size">
                <a href="#" class="pull-right"><i class="fa fa-cloud-download"></i></a>
            </span>
     	</div>
        </li>
    @else
		<li>
          <span class="mailbox-attachment-icon no-print"><a href="#" target="_blank" ><i class="fa  fa-file-archive-o"></i></a></span>

          <div class="mailbox-attachment-info">
            <a href="#" class="mailbox-attachment-name" target="_blank" >{{$documt->nombre}}</a>
            <span class="mailbox-attachment-size">
                <a href="#" class="pull-right"><i class="fa fa-cloud-download"></i></a>
            </span>
     	</div>
        </li>
	@endif
  @endif
@endforeach
