<div class="container-fluid">
       <div class="row">
           <div class="col-12">
               <div class="card">
                   <div class="card-header">
                       <h4>Daftar Pendaftaran Santri</h4>
                   </div>
                   <div class="card-body">
                       <table class="table table-striped">
                           <thead>
                               <tr>
                                   <th>ID</th>
                                   <th>Nama Lengkap</th>
                                   <th>Jenis Kelamin</th>
                                   <th>Status Kesantrian</th>
                                   <th>Alamat</th>
                                   <th>Created At</th>
                               </tr>
                           </thead>
                           <tbody>
                               @foreach ($registrations as $registration)
                                   <tr>
                                       <td>{{ $registration->id }}</td>
                                       <td>{{ $registration->nama_lengkap }}</td>
                                       <td>{{ $registration->jenis_kelamin }}</td>
                                       <td>{{ $registration->status_kesantrian }}</td>
                                       <td>{{ $registration->alamat }}</td>
                                       <td>{{ $registration->created_at }}</td>
                                   </tr>
                               @endforeach
                           </tbody>
                       </table>
                   </div>
               </div>
           </div>
       </div>
   </div>