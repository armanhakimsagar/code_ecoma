@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <div class="padding-bottom padding-top">
        <div class="container">
            <div class="row">
                @if (auth()->user())
                    <div class="col-xl-3">
                        <div class="dashboard-menu">
                            @include($activeTemplate . 'user.partials.dp')
                            <ul>
                                @include($activeTemplate . 'user.partials.sidebar')
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="@auth col-xl-9 @else col-xl-12 @endauth">
                    <div class="card border-0 shadow-md">
                        <div class="card-header bg-transparent d-flex flex-wrap justify-content-between align-items-center">
                            <h5 class="card-title mt-0 mb-0">
                                @php echo $myTicket->statusBadge @endphp
                                [@lang('Ticket')#{{ $myTicket->ticket }}] {{ $myTicket->subject }}
                            </h5>

                            @if ($myTicket->status != Status::TICKET_CLOSE && $myTicket->user)
                                <button class="btn btn--danger  confirmationBtn h-40" type="button" data-question="@lang('Are you sure to close this ticket?')" data-action="{{ route('ticket.close', $myTicket->id) }}"><i class="la la-times-circle"></i>
                                </button>
                            @endif
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('ticket.reply', $myTicket->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row justify-content-between">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea name="message" class="form-control" id="inputMessage" placeholder="@lang('Your Reply')" rows="4" cols="10" required></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        <button type="button" class="btn btn-dark btn-sm addAttachment my-2"> <i class="fas fa-plus"></i> @lang('Add Attachment') </button>
                                        <p class="mb-2"><span class="text--info">@lang('Max 5 files can be uploaded | Maximum upload size is ' . convertToReadableSize(ini_get('upload_max_filesize')) . ' | Allowed File Extensions: .jpg, .jpeg, .png, .pdf, .doc, .docx')</span></p>
                                        <div class="row fileUploadsContainer gy-2">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn--base w-100 my-2" type="submit"><i class="la la-fw la-lg la-reply"></i> @lang('Reply')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card border-0 shadow-md mt-4">
                        <div class="card-body">
                            @forelse($messages as $message)
                                @if ($message->admin_id == 0)
                                    <div class="row border border-primary border-radius-3 my-3 py-3 mx-2">
                                        <div class="col-md-3 border-end text-end">
                                            <h5 class="my-3">{{ $message->ticket->name }}</h5>
                                        </div>
                                        <div class="col-md-9">
                                            <p class="text-muted fw-bold my-3">
                                                @lang('Posted on') {{ showDateTime($message->created_at, 'l, dS F Y @ h:i a') }}</p>
                                            <p>{{ $message->message }}</p>
                                            @if ($message->attachments->count() > 0)
                                                <div class="mt-2">
                                                    @foreach ($message->attachments as $k => $image)
                                                        <a href="{{ route('ticket.download', encrypt($image->id)) }}" class="me-3"><i class="fa-regular fa-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="row border border-warning border-radius-3 my-3 py-3 mx-2 reply-bg">
                                        <div class="col-md-3 border-end text-end">
                                            <h5 class="my-3">{{ $message->admin->name }}</h5>
                                            <p class="lead text-muted">@lang('Staff')</p>
                                        </div>
                                        <div class="col-md-9">
                                            <p class="text-muted fw-bold my-3">
                                                @lang('Posted on') {{ showDateTime($message->created_at, 'l, dS F Y @ h:i a') }}</p>
                                            <p>{{ $message->message }}</p>
                                            @if ($message->attachments->count() > 0)
                                                <div class="mt-2">
                                                    @foreach ($message->attachments as $k => $image)
                                                        <a href="{{ route('ticket.download', encrypt($image->id)) }}" class="me-3"><i class="fa-regular fa-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="empty-message text-center">
                                    <img src="{{ asset('assets/images/empty_list.png') }}" alt="empty">
                                    <h5 class="text-muted">@lang('No replies found here!')</h5>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addAttachment').on('click', function() {
                fileAdded++;
                if (fileAdded == 5) {
                    $(this).attr('disabled', true)
                }
                $(".fileUploadsContainer").append(`
                    <div class="col-lg-4 col-md-12 removeFileInput">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="file" name="attachments[]" class="form-control" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
                                <button type="button" class="input-group-text removeFile bg--danger text--white border-0"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                `)
            });
            $(document).on('click', '.removeFile', function() {
                $('.addAttachment').removeAttr('disabled', true)
                fileAdded--;
                $(this).closest('.removeFileInput').remove();
            });
        })(jQuery);
    </script>
@endpush
