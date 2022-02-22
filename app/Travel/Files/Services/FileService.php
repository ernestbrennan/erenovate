<?php

namespace App\Travel\Files\Services;

use App\Travel\Base\BaseFormRequest;
use App\Travel\Files\Repositories\Interfaces\FileRepositoryInterface;
use App\Travel\Notes\Repositories\Interfaces\NoteRepositoryInterface;
use App\Travel\Projects\Exceptions\NotFoundGuaranteeProjectErrorException;
use App\Travel\Projects\Repositories\Interfaces\GuaranteeProjectRepositoryInterface;
use Auth;

class FileService
{
    /**
     * @var FileRepositoryInterface
     */
    private $file_repository;

    /**
     * @var GuaranteeProjectRepositoryInterface
     */
    private $guarantee_project_repository;

    /**
     * ChatService constructor.
     *
     * @param FileRepositoryInterface $file_repository
     * @param GuaranteeProjectRepositoryInterface $guarantee_project_repository
     */
    public function __construct(FileRepositoryInterface $file_repository,
                                GuaranteeProjectRepositoryInterface $guarantee_project_repository)
    {
        $this->file_repository = $file_repository;
        $this->guarantee_project_repository = $guarantee_project_repository;
    }


    /**
     * @param BaseFormRequest $request
     * @return mixed
     * @throws NotFoundGuaranteeProjectErrorException
     */
    public function getHistoryByProject($request)
    {
        $guarantee_project_id = $request->input('guarantee_project_id');
        
        $guarantee_project = $this->guarantee_project_repository
            ->getGuaranteeProjectById($guarantee_project_id, [
                'contractor:userId,firstname,lastname',
                'homeowner:userId,firstname,lastname',
                'notes'=> function ($query) {
                    $query->where('user_id', Auth::id());
                    $query->whereHas('files');
                },
                'notes.files',
                'chat.messages' => function ($query) {
                    $query->whereHas('files');
                },
                'chat.messages.files',
                'contract',
                'contract.last_draft',
                'contract.last_draft.contract_signed',
                'contract.last_draft.contract_signed.file',
                'contract.last_draft.batches',
                'contract.last_draft.batches.files',
                'contract.last_draft.milestones',
                'contract.last_draft.milestones.milestone_content',
                'contract.last_draft.milestones.milestone_content.batches',
                'contract.last_draft.milestones.milestone_content.batches.files',
                'contract.last_draft.milestones.milestone_content.material_files',
                'contract.last_draft.milestones.invoice',
                'contract.last_draft.milestones.invoice.batches',
                'contract.last_draft.milestones.invoice.batches.user',
                'contract.last_draft.milestones.invoice.batches.files',
                'contract.last_draft.milestones.invoice.material_files',
            ]);
        
        $entities = [];

        foreach ($guarantee_project['notes']->toArray() as $note) {
            $entities[] = [
                'sender_id' => $note['user_id'],
                'content' => $note['content'],
                'files' => $note['files'],
                'created_at' => $note['created_at']
            ];
        }

        foreach ($guarantee_project['chat']['messages'] as $message) {
            $entities[] = $message->toArray();
        }

        $last_draft = $guarantee_project['contract']['last_draft'];

        if ($last_draft) {
            $last_draft = $last_draft->toArray();

            $entities[] = [
                'sender_id' => $last_draft['user_id'],
                'content' => $last_draft['contract_signed']['description'],
                'files' => [$last_draft['contract_signed']['file']],
                'created_at' => $last_draft['contract_signed']['created_at']
            ];

            foreach ($last_draft['batches'] as $batch) {

                $entities[] = [
                    'sender_id' => $batch['user_id'],
                    'content' => $batch['description'],
                    'files' => $batch['files'],
                    'created_at' => $batch['created_at']
                ];
            }

            foreach ($last_draft['milestones'] as $milestone) {

                foreach ($milestone['milestone_content']['batches'] as $batch) {

                    $entities[] = [
                        'sender_id' => $batch['user_id'],
                        'content' => $batch['description'],
                        'files' => $batch['files'],
                        'created_at' => $batch['created_at']
                    ];
                }

                if (count($milestone['milestone_content']['material_files']) ) {

                    $entities[] = [
                        'sender_id' => $last_draft['user_id'],
                        'content' => '',
                        'files' => $milestone['milestone_content']['material_files'],
                        'created_at' => $last_draft['created_at']
                    ];
                }

                if ($milestone['invoice']) {

                    foreach ($milestone['invoice']['batches'] as $batch) {

                        $entities[] = [
                            'sender_id' => $batch['user_id'],
                            'content' => $batch['description'],
                            'files' => $batch['files'],
                            'created_at' => $batch['created_at']
                        ];
                    }

                    if (count($milestone['invoice']['material_files'])) {

                        $entities[] = [
                            'sender_id' => $last_draft['user_id'],
                            'content' => '',
                            'files' => $milestone['invoice']['material_files'],
                            'created_at' => $last_draft['created_at']
                        ];
                    }
                }
            }
        }

        $target_user = $this->guarantee_project_repository->getInterlocutorUser($guarantee_project);
        $entities = collect($entities)->sortByDesc('created_at')->values()->toArray();

        return compact('entities', 'target_user');
    }
}