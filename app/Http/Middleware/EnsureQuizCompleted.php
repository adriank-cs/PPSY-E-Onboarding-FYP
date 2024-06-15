<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ItemProgress;
use App\Models\ChapterProgress;
use App\Models\Item;
use App\Models\Chapter;

class EnsureQuizCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $itemId = $request->route('itemId');
        $user = auth()->user();
        $companyId = $user->companyUser->CompanyID;

        // Get the current item and its order
        $currentItem = Item::findOrFail($itemId);
        $currentItemOrder = $currentItem->order;
        $currentChapterId = $currentItem->chapter_id;
        $moduleId = $currentItem->chapter->module_id;

        // Get the previous item in the current chapter
        $previousItem = Item::where('chapter_id', $currentChapterId)
                            ->where('order', '<', $currentItemOrder)
                            ->orderBy('order', 'desc')
                            ->first();

        // Check the previous chapter if there's no previous item in the current chapter
        if (!$previousItem) {
            // Get all chapters in the module ordered by their ID
            $chapters = Chapter::where('module_id', $moduleId)->orderBy('id')->get();

            // Find the previous chapter
            $previousChapter = null;
            foreach ($chapters as $index => $chapter) {
                if ($chapter->id == $currentChapterId && $index > 0) {
                    $previousChapter = $chapters[$index - 1];
                    break;
                }
            }

            // If a previous chapter exists, check its completion status
            if ($previousChapter) {
                $previousChapterProgress = ChapterProgress::where('UserID', $user->id)
                                                          ->where('CompanyID', $companyId)
                                                          ->where('ModuleID', $moduleId)
                                                          ->where('ChapterID', $previousChapter->id)
                                                          ->first();

                if (!$previousChapterProgress || $previousChapterProgress->IsCompleted != 1) {
                    // Get the latest item progress in the previous chapter
                    $latestItemProgress = ItemProgress::where('UserID', $user->id)
                                                      ->where('CompanyID', $companyId)
                                                      ->where('ModuleID', $moduleId)
                                                      ->whereHas('item', function ($query) use ($previousChapter) {
                                                          $query->where('chapter_id', $previousChapter->id);
                                                      })
                                                      ->whereNull('IsCompleted')
                                                      ->orderBy('order')
                                                      ->first();

                    if ($latestItemProgress) {
                        return redirect()->route('employee.view_page', ['itemId' => $latestItemProgress->ItemID])
                                         ->with('error', 'You must complete the previous chapter before proceeding.');
                    }
                }
            }
        } else {
            $previousItemProgress = ItemProgress::where('UserID', $user->id)
                                                ->where('ItemID', $previousItem->id)
                                                ->first();

            // Check if the previous item is not completed
            if (!$previousItemProgress || $previousItemProgress->IsCompleted != 1) {
                return redirect()->route('employee.view_page', ['itemId' => $previousItem->id])
                                 ->with('error', 'You must complete the previous item before proceeding.');
            }
        }

        return $next($request);
    }
    
}