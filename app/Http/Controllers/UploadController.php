<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Imagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function mime2ext($mime)
    {
        $all_mimes = '{"png":["image\/png","image\/x-png"],"bmp":["image\/bmp","image\/x-bmp","image\/x-bitmap","image\/x-xbitmap","image\/x-win-bitmap","image\/x-windows-bmp","image\/ms-bmp","image\/x-ms-bmp","application\/bmp","application\/x-bmp","application\/x-win-bitmap"],"gif":["image\/gif"],"jpeg":["image\/jpeg","image\/pjpeg"],"xspf":["application\/xspf+xml"],"vlc":["application\/videolan"],"wmv":["video\/x-ms-wmv","video\/x-ms-asf"],"au":["audio\/x-au"],"ac3":["audio\/ac3"],"flac":["audio\/x-flac"],"ogg":["audio\/ogg","video\/ogg","application\/ogg"],"kmz":["application\/vnd.google-earth.kmz"],"kml":["application\/vnd.google-earth.kml+xml"],"rtx":["text\/richtext"],"rtf":["text\/rtf"],"jar":["application\/java-archive","application\/x-java-application","application\/x-jar"],"zip":["application\/x-zip","application\/zip","application\/x-zip-compressed","application\/s-compressed","multipart\/x-zip"],"7zip":["application\/x-compressed"],"xml":["application\/xml","text\/xml"],"svg":["image\/svg+xml"],"3g2":["video\/3gpp2"],"3gp":["video\/3gp","video\/3gpp"],"mp4":["video\/mp4"],"m4a":["audio\/x-m4a"],"f4v":["video\/x-f4v"],"flv":["video\/x-flv"],"webm":["video\/webm"],"aac":["audio\/x-acc"],"m4u":["application\/vnd.mpegurl"],"pdf":["application\/pdf","application\/octet-stream"],"pptx":["application\/vnd.openxmlformats-officedocument.presentationml.presentation"],"ppt":["application\/powerpoint","application\/vnd.ms-powerpoint","application\/vnd.ms-office","application\/msword"],"docx":["application\/vnd.openxmlformats-officedocument.wordprocessingml.document"],"xlsx":["application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application\/vnd.ms-excel"],"xl":["application\/excel"],"xls":["application\/msexcel","application\/x-msexcel","application\/x-ms-excel","application\/x-excel","application\/x-dos_ms_excel","application\/xls","application\/x-xls"],"xsl":["text\/xsl"],"mpeg":["video\/mpeg"],"mov":["video\/quicktime"],"avi":["video\/x-msvideo","video\/msvideo","video\/avi","application\/x-troff-msvideo"],"movie":["video\/x-sgi-movie"],"log":["text\/x-log"],"txt":["text\/plain"],"css":["text\/css"],"html":["text\/html"],"wav":["audio\/x-wav","audio\/wave","audio\/wav"],"xhtml":["application\/xhtml+xml"],"tar":["application\/x-tar"],"tgz":["application\/x-gzip-compressed"],"psd":["application\/x-photoshop","image\/vnd.adobe.photoshop"],"exe":["application\/x-msdownload"],"js":["application\/x-javascript"],"mp3":["audio\/mpeg","audio\/mpg","audio\/mpeg3","audio\/mp3"],"rar":["application\/x-rar","application\/rar","application\/x-rar-compressed"],"gzip":["application\/x-gzip"],"hqx":["application\/mac-binhex40","application\/mac-binhex","application\/x-binhex40","application\/x-mac-binhex40"],"cpt":["application\/mac-compactpro"],"bin":["application\/macbinary","application\/mac-binary","application\/x-binary","application\/x-macbinary"],"oda":["application\/oda"],"ai":["application\/postscript"],"smil":["application\/smil"],"mif":["application\/vnd.mif"],"wbxml":["application\/wbxml"],"wmlc":["application\/wmlc"],"dcr":["application\/x-director"],"dvi":["application\/x-dvi"],"gtar":["application\/x-gtar"],"php":["application\/x-httpd-php","application\/php","application\/x-php","text\/php","text\/x-php","application\/x-httpd-php-source"],"swf":["application\/x-shockwave-flash"],"sit":["application\/x-stuffit"],"z":["application\/x-compress"],"mid":["audio\/midi"],"aif":["audio\/x-aiff","audio\/aiff"],"ram":["audio\/x-pn-realaudio"],"rpm":["audio\/x-pn-realaudio-plugin"],"ra":["audio\/x-realaudio"],"rv":["video\/vnd.rn-realvideo"],"jp2":["image\/jp2","video\/mj2","image\/jpx","image\/jpm"],"tiff":["image\/tiff"],"eml":["message\/rfc822"],"pem":["application\/x-x509-user-cert","application\/x-pem-file"],"p10":["application\/x-pkcs10","application\/pkcs10"],"p12":["application\/x-pkcs12"],"p7a":["application\/x-pkcs7-signature"],"p7c":["application\/pkcs7-mime","application\/x-pkcs7-mime"],"p7r":["application\/x-pkcs7-certreqresp"],"p7s":["application\/pkcs7-signature"],"crt":["application\/x-x509-ca-cert","application\/pkix-cert"],"crl":["application\/pkix-crl","application\/pkcs-crl"],"pgp":["application\/pgp"],"gpg":["application\/gpg-keys"],"rsa":["application\/x-pkcs7"],"ics":["text\/calendar"],"zsh":["text\/x-scriptzsh"],"cdr":["application\/cdr","application\/coreldraw","application\/x-cdr","application\/x-coreldraw","image\/cdr","image\/x-cdr","zz-application\/zz-winassoc-cdr"],"wma":["audio\/x-ms-wma"],"vcf":["text\/x-vcard"],"srt":["text\/srt"],"vtt":["text\/vtt"],"ico":["image\/x-icon","image\/x-ico","image\/vnd.microsoft.icon"],"csv":["text\/x-comma-separated-values","text\/comma-separated-values","application\/vnd.msexcel"],"json":["application\/json","text\/json"]}';
        $all_mimes = json_decode($all_mimes, true);
        foreach ($all_mimes as $key => $value) {
            if (false !== array_search($mime, $value)) {
                return $key;
            }
        }

        return false;
    }

    public function uploadImagem(Request $request)
    {
        \DB::beginTransaction();
        try {
            // Define a pasta dentro de storage/app/public/
            $pastaDestino = 'images/uploaded';
            if ($request->destination) {
                $pastaDestino = 'images/'.$request->destination;
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file');

                $nomeArquivo = $file->hashName();

                $caminhoSalvo = $file->storeAs($pastaDestino, $nomeArquivo, 'public');

                $img = new Imagem();

                // Para acessar no front: asset('storage/' . $caminhoSalvo)
                // No banco, salvamos o caminho relativo ou a URL completa:
                $img->url = Storage::url($caminhoSalvo); // Gera /storage/images/produtos/arquivo.jpg

                $img->save();
            }

            \DB::commit();

            return $img->id;
        } catch (\Exception $e) {
            \DB::rollback();
            // Se der erro no banco, é boa prática apagar a imagem que acabou de subir
            if (isset($caminhoSalvo)) {
                Storage::disk('public')->delete($caminhoSalvo);
            }

            Log::error($e->getMessage());
            // Evite passar o objeto de erro inteiro ($e) para o abort em produção por segurança
            abort(500, 'Erro ao realizar upload.');
        }
    }

    public function uploadArquivo(Request $request)
    {
        // 1. Validação é CRUCIAL. Se o arquivo for maior que o limite do PHP,
        // o Laravel vai falhar aqui e te mostrar o erro real, em vez de silenciar.
        $request->validate([
            'file' => 'required|file|max:20480', // Aumentei para 20MB para suportar fotos de alta resolução
            'tipo_documento' => 'required|string',
        ], [
            'file.max' => 'O arquivo é muito grande. O tamanho máximo é 20MB.',
            'file.required' => 'Nenhum arquivo foi enviado.',
        ]);

        \DB::beginTransaction();
        try {
            // Pega o arquivo validado
            $file = $request->file('file');

            // Define a pasta de destino. Usa 'arquivos' como padrão se não houver 'destination' no request
            $pasta = 'arquivos';
            if ($request->has('destination')) {
                $pasta .= '/'.$request->input('destination');
            }

            // Gera um nome seguro e único para o arquivo
            $nomeOriginal = $file->getClientOriginalName();
            $extensao = $file->getClientOriginalExtension();
            // Hash do nome garante que não haja caracteres especiais que quebrem URLs
            $nomeArquivoSeguro = md5(time().$nomeOriginal).'.'.$extensao;

            // 2. Salva no disco 'public' para ser acessível via web.
            // É importante especificar o disco ('public') se você quiser gerar URLs depois.
            $path = $file->storeAs($pasta, $nomeArquivoSeguro, 'public');

            // 3. Cria o registro no banco
            $arquivo = Arquivo::create([
                'user_id' => auth()->id(),
                'tipo_documento' => $request->input('tipo_documento'),
                'nome_original' => $nomeOriginal,
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'status' => 'enviado',
            ]);

            \DB::commit();

            // Retorna o objeto completo ou use o seu Resource se preferir:
            // return new ArquivosResource($arquivo);
            return response()->json($arquivo, 201);
        } catch (\Exception $e) {
            \DB::rollBack();
            // Log o erro real para você poder depurar
            \Log::error('Erro no uploadArquivo: '.$e->getMessage());

            // Retorne uma mensagem genérica para o usuário, mas mantenha o status 500
            return response()->json(['message' => 'Erro ao processar o arquivo. Tente novamente.'], 500);
        }
    }

    public function getUrl($id)
    {
        return Imagem::find($id)->url;
    }

    public function getArquivo($id)
    {
        $arquivo = Arquivo::find($id);

        if (!Storage::exists($arquivo->url)) {
            abort(404, 'Arquivo não encontrado.');
        }

        return Storage::download($arquivo->url, $arquivo->nome_original);
    }

    public function getArquivoDet($id)
    {
        return Arquivo::select('id', 'titulo')->findOrFail($id);
    }

    public function showImage($id)
    {
        $imagem = Imagem::findOrFail($id);
        $path = public_path($imagem->url);

        return response()->file($path);
    }

    public function showArquivo($id)
    {
        $arquivo = Arquivo::find($id);

        if (!Storage::exists($arquivo->url)) {
            abort(404, 'Arquivo não encontrado.');
        }

        $path = storage_path('app/'.$arquivo->url);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$arquivo->titulo.'"',
        ]);
    }
}
