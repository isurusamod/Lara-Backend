<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Template;

class PDFController extends Controller
{
    public function uploadPDF(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pdf' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file = $request->file('pdf');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('pdfs', $filename, 'public');

        return response()->json([
            'message' => 'PDF uploaded successfully',
            'filename' => $filename,
            'path' => $path,
            'url' => Storage::url($path)
        ]);
    }

    public function processPDF(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pdf_path' => 'required|string',
            'fields' => 'required|array',
            'text_data' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Here you would implement PDF processing logic
        // For now, we'll return a success response
        return response()->json([
            'message' => 'PDF processed successfully',
            'processed_pdf_url' => '/storage/processed_pdfs/processed_' . time() . '.pdf'
        ]);
    }

    public function saveTemplate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'fields' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $template = Template::create([
            'name' => $request->name,
            'fields' => json_encode($request->fields),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'message' => 'Template saved successfully',
            'template' => $template
        ]);
    }

    public function getTemplates()
    {
        $templates = Template::all();
        return response()->json($templates);
    }

    public function getTemplate($id)
    {
        $template = Template::find($id);
        
        if (!$template) {
            return response()->json(['message' => 'Template not found'], 404);
        }

        return response()->json([
            'id' => $template->id,
            'name' => $template->name,
            'fields' => json_decode($template->fields, true)
        ]);
    }

    public function deleteTemplate($id)
    {
        $template = Template::find($id);
        
        if (!$template) {
            return response()->json(['message' => 'Template not found'], 404);
        }

        $template->delete();
        return response()->json(['message' => 'Template deleted successfully']);
    }
}