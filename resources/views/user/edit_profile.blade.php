@extends('layout.next')

@section('breadcrumb')
<li>
    <a href="{{ route('profile', ['id' => $user->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
    </a>
</li>
<li>
    <a href="{{ route('user_edit_profile', ['id' => $user->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('common.edit') }}</span>
    </a>
</li>
@endsection

@section('content')
    <div class="block">
        <div class="block__title">{{ trans('user.edit-profile') }}</div>
        <div class="block__content">
            <form action="{{ route('user_edit_profile', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mbox mbox--small-bottom flex">
                    <span class="badge badge--centered col col--medium mbox--small-right">{{ trans('user.avatar') }}</span>
                    <input type="file" name="image">
                </div>

                <div class="flex mbox mbox--small-bottom">
                    <div class="badge badge--centered mbox mbox--mini-right col col--medium">{{ trans('user.custom-title') }}</div>
                    <input type="text" name="title" class="flex__expanded" value="{{ $user->title }}">
                </div>

                <div class="mbox mbox--small-bottom">
                    <div class="badge badge--centered mbox--mini-bottom col col--medium">{{ trans('user.about-me') }}</div>
                    <textarea name="about" class="textarea textarea--vertical">{{ $user->about }}</textarea>
                </div>

                <div class="mbox mbox--small-botttom">
                    <div class="badge badge--centered mbox mbox--mini-bottom col col--medium">{{ trans('user.forum-signature') }}</div>
                    <textarea name="signature" class="textarea textarea--vertical">{{ $user->signature }}</textarea>
                </div>

                <button type="submit" class="btn">
                    <i class="far fa-paper-plane"></i>
                    {{ trans('common.submit') }}
                </button>
            </form>
        </div>
    </div>
@endsection