<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Comment;

class CommentQueries
{
    public function create(int $deficiencyId, int $commentBy, string $comment): Comment
    {
        return Comment::query()
            ->create([
                'deficiency_id' => $deficiencyId,
                'comment_by'    => $commentBy,
                'comment'       => $comment
            ]);
    }

    public function delete(int $deficiencyId, int $commentBy): void
    {
        Comment::query()
            ->where([
                'deficiency_id' => $deficiencyId,
                'comment_by'    => $commentBy,
            ])
            ->delete();
    }
}
