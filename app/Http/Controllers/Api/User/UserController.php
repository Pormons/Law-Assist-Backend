<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Lawyer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenAI;

class UserController extends Controller
{
    public function createChat(Request $request)
    {
        $user = Auth::user();

        // Check if the request contains a chatId
        $chatId = $request->input('chatId');

        if ($chatId) {
            // Retrieve the existing chat by ID
            $chat = $user->chats()->find($chatId);
        } else {
            // Create a new chat if no chatId is provided
            $chat = $user->chats()->create([
                'title' => $request->input('chat'),
            ]);
        }

        // Check if the chat was successfully retrieved or created
        if ($chat) {
            // Save user message to chat history
            $this->saveMessage($chat, false, $request->input('chat'));

            // Generate a bot response using the chatBot method
            $botResponse = $this->chatBot($request->input('chat'));

            // Save bot response to chat history
            $this->saveMessage($chat, true, $botResponse);

            return response()->json(['id' => now(), 'message' => $botResponse, 'bot' => true, 'chatId' => $chat->id], 201);
        }
    }

    public function LawyerChat(Request $request)
    {
            // Generate a bot response using the chatBot method
            $botResponse = $this->chatBot($request->input('chat'));

            return response()->json([
                'id' => now(),
                'bot' => true,
                'message' => $botResponse,

            ], 201);
    }

    private function saveMessage($chat, $isBot, $message)
    {
        $chat->chatHistory()->create([
            'bot' => $isBot,
            'message' => $message,
        ]);
    }

    private function chatBot($userMessage)
    {
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);

        $result = $client->chat()->create([
            'model' => 'gpt-3.5-turbo-0613',
            'messages' => [
                ['role' => 'user', 'content' => $userMessage],
            ],
        ]);

        return $result->choices[0]->message->content;
    }

    public function getChatHistory($chatId)
    {
        $user = Auth::user();
        $chat = $user->chats()->find($chatId);

        if (!$chat) {
            return response()->json(['error' => 'Chat not found'], 404);
        }

        $history = $chat->chatHistory()->orderBy('created_at', 'asc')->get();
        $originalDateString = $chat->created_at;
        $originalDate = new \DateTime($originalDateString);

        // Format the date
        $formattedDateString = $originalDate->format('F j, Y : g:i A');
        return response()->json(['chatId' => $chatId,'title'=>$chat->title, 'date'=> $formattedDateString, 'history' => $history], 200);
    }

    public function deleteChat($id)
    {
        $user = Auth::user();
        $chat = $user->chats()->find($id);
        $chat->delete();
    }

    public function getChats()
    {
        $user = Auth::user();
        $chats = $user->chats;

        return $chats;
    }

    public function searchLawyer(Request $request)
    {
        $query = User::where('lawyer', true);

        if ($request->search) {
            $searchTerm = '%' . $request->search . '%';

            $query->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', $searchTerm)
                      ->orWhere('address', 'LIKE', $searchTerm)
                      ->orWhere('region', 'LIKE', $searchTerm);
            });
        }

        $lawyers = $query->get();

        return $lawyers;
    }

    public function lawyerInfo($id)
    {
        $query = User::where('lawyer', true)->findorFail($id);
        return $query;
    }
}
