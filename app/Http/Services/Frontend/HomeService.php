<?php

namespace App\Http\Services\Frontend;

use App\Models\Event;
use App\Models\JobPost;
use App\Models\News;
use App\Models\Notice;
use App\Models\PhotoGallery;
use App\Models\Story;
use App\Models\User;
use App\Traits\ResponseTrait;

class HomeService
{
    use ResponseTrait;

    public function getUpcomingEvent()
    {
        // Removed: where('events.tenant_id', getTenantId())
        return Event::where('date', '>', now())
            ->orderBy('date', 'ASC')
            ->where('status', 1) // Assuming 1 is STATUS_ACTIVE
            ->with('category')
            ->get();
    }

    public function getPhotoGalleries()
    {
        // Removed: where('photo_galleries.tenant_id', getTenantId())
        return PhotoGallery::where('status', 1)->get();
    }

    public function getAlumni($limit = 6)
    {
        // Removed: where('users.tenant_id', getTenantId())
        return User::where('users.status', 1)
            ->join('alumnus', 'users.id', '=', 'alumnus.user_id')
            ->leftJoin('batches', 'batches.id', '=', 'alumnus.batch_id')
            ->leftJoin('departments', 'departments.id', '=', 'alumnus.department_id')
            ->orderBy('users.created_at', 'DESC')
            ->select('users.name', 'users.id', 'users.image', 'batches.name as batch_name', 'departments.name as department_name')
            ->limit($limit)
            ->get();
    }
    
    public function getNews($limit = 3)
    {
        return News::where('status', 1)->orderBy('id', 'DESC')->limit($limit)->get();
    }
}