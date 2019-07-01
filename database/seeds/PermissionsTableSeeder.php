<?php

use Illuminate\Database\Seeder;
use Caffeinated\Shinobi\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	//### Users ###
        Permission::create([
        	'name'			=> 'Listar Usuarios',
        	'slug'			=> 'users.index',
        	'description'	=> 'Lista y navega todos los Usuarios del sistema',
        ]);
        Permission::create([
        	'name'			=> 'Crear Usuarios',
        	'slug'			=> 'users.create',
        	'description'	=> 'Crear un nuevo Usuario del sistema',
        ]);
        Permission::create([
        	'name'			=> 'Ver el detalle de Usuario',
        	'slug'			=> 'users.show',
        	'description'	=> 'Ver en detalle cada Usuario del sistema',
        ]);
        Permission::create([
        	'name'			=> 'Edición de Usuarios',
        	'slug'			=> 'users.edit',
        	'description'	=> 'Editar cualquier dato de un Usuario del sistema',
        ]);
        Permission::create([
        	'name'			=> 'Eliminar Usuarios',
        	'slug'			=> 'users.destroy',
        	'description'	=> 'Elimina cualquier Usuario del sistema',
        ]);

    	//### Roles ###
        Permission::create([
        	'name'			=> 'Listar Roles',
        	'slug'			=> 'roles.index',
        	'description'	=> 'Lista y navega todos los Roles del sistema',
        ]);
        Permission::create([
        	'name'			=> 'Crear Roles',
        	'slug'			=> 'roles.create',
        	'description'	=> 'Crear un nuevo Rol del sistema',
        ]);
        Permission::create([
        	'name'			=> 'Ver el detalle de Rol',
        	'slug'			=> 'roles.show',
        	'description'	=> 'Ver en detalle cada Rol del sistema',
        ]);
        Permission::create([
        	'name'			=> 'Edición de Roles',
        	'slug'			=> 'roles.edit',
        	'description'	=> 'Editar cualquier dato de un Rol del sistema',
        ]);
        Permission::create([
        	'name'			=> 'Eliminar Roles',
        	'slug'			=> 'roles.destroy',
        	'description'	=> 'Elimina cualquier Rol del sistema',
        ]);

    	//### Areas ###
        Permission::create([
        	'name'			=> 'Listar Áreas',
        	'slug'			=> 'areas.index',
        	'description'	=> 'Lista y navega todas las Áreas de la granja',
        ]);
        Permission::create([
        	'name'			=> 'Crear Áreas',
        	'slug'			=> 'areas.create',
        	'description'	=> 'Crear una nueva Área de la granja',
        ]);
        Permission::create([
            'name'          => 'API Crear Áreas',
            'slug'          => 'areas.store',
            'description'   => 'Crear una nueva Área de la granja',
        ]);
        Permission::create([
        	'name'			=> 'Ver el detalle de Área',
        	'slug'			=> 'areas.show',
        	'description'	=> 'Ver en detalle cada Área de la granja',
        ]);
        Permission::create([
        	'name'			=> 'Edición de Áreas',
        	'slug'			=> 'areas.edit',
        	'description'	=> 'Editar cualquier dato de un Área de la granja',
        ]);
        Permission::create([
            'name'          => 'API Edición de Áreas',
            'slug'          => 'areas.update',
            'description'   => 'Editar cualquier dato de un Área de la granja',
        ]);
        Permission::create([
        	'name'			=> 'Eliminar Áreas',
        	'slug'			=> 'areas.destroy',
        	'description'	=> 'Elimina cualquier Área de la granja',
        ]);

        //### Lct1s ###
        Permission::create([
            'name'          => 'Listar Ubicaciones UNO',
            'slug'          => 'lct1s.index',
            'description'   => 'Lista y navega todas las Ubicaciones UNO de un Área',
        ]);
        Permission::create([
            'name'          => 'Crear Ubicaciones UNO',
            'slug'          => 'lct1s.create',
            'description'   => 'Crear una nueva Ubicación UNO de un Área',
        ]);
        Permission::create([
            'name'          => 'API Crear Ubicaciones UNO',
            'slug'          => 'lct1s.store',
            'description'   => 'Crear una nueva Ubicación UNO de un Área',
        ]);
        Permission::create([
            'name'          => 'Ver el detalle de Ubicación UNO',
            'slug'          => 'lct1s.show',
            'description'   => 'Ver en detalle cada Ubicación UNO de un Área',
        ]);
        Permission::create([
            'name'          => 'Edición de Ubicaciones UNO',
            'slug'          => 'lct1s.edit',
            'description'   => 'Editar cualquier dato de una Ubicación UNO de un Área',
        ]);
        Permission::create([
            'name'          => 'API Edición de Ubicaciones UNO',
            'slug'          => 'lct1s.update',
            'description'   => 'Editar cualquier dato de una Ubicación UNO de un Área',
        ]);
        Permission::create([
            'name'          => 'Eliminar Ubicaciones UNO',
            'slug'          => 'lct1s.destroy',
            'description'   => 'Elimina cualquier Ubicación UNO de un Área',
        ]);

        //### Lct2s ###
        Permission::create([
            'name'          => 'Listar Ubicaciones DOS',
            'slug'          => 'lct2s.index',
            'description'   => 'Lista y navega todas las Ubicaciones DOS de una Ucicación UNO y un Área de la granja',
        ]);
        Permission::create([
            'name'          => 'Crear Ubicaciones DOS',
            'slug'          => 'lct2s.create',
            'description'   => 'Crear una nueva Ubicación DOS de una Ubicación UNO y un Área de la granja',
        ]);
        Permission::create([
            'name'          => 'API Crear Ubicaciones DOS',
            'slug'          => 'lct2s.store',
            'description'   => 'Crear una nueva Ubicación DOS de una Ubicación UNO y un Área de la granja',
        ]);
        Permission::create([
            'name'          => 'Ver el detalle de una Ubicación DOS perteneciente a una Ubicación UNO y un Área de la granja',
            'slug'          => 'lct2s.show',
            'description'   => 'Ver en detalle cada Ubicación DOS de una Ubicación UNO y un Área de la granja',
        ]);
        Permission::create([
            'name'          => 'Edición de Ubicaciones DOS',
            'slug'          => 'lct2s.edit',
            'description'   => 'Editar cualquier dato de una Ubicación DOS perteneciente a una Ubicación UNO y un Área de la granja',
        ]);
        Permission::create([
            'name'          => 'API Edición de Ubicaciones DOS',
            'slug'          => 'lct2s.update',
            'description'   => 'Editar cualquier dato de una Ubicación DOS perteneciente a una Ubicación UNO y un Área de la granja',
        ]);
        Permission::create([
            'name'          => 'Eliminar Ubicaciones DOS',
            'slug'          => 'lct2s.destroy',
            'description'   => 'Elimina una Ubicación DOS de una Ubicación UNO y un Área de la granja',
        ]);

        //### Responsibles ###
        Permission::create([
            'name'          => 'Listar Responsables',
            'slug'          => 'Responsibles.index',
            'description'   => 'Lista y navega todos los Responsables',
        ]);
        Permission::create([
            'name'          => 'Crear Responsables',
            'slug'          => 'Responsibles.create',
            'description'   => 'Crear un Responsable',
        ]);
        Permission::create([
            'name'          => 'API Crear Responsables',
            'slug'          => 'Responsibles.store',
            'description'   => 'Crear un Responsable',
        ]);
        Permission::create([
            'name'          => 'Ver el detalle de un Responsable',
            'slug'          => 'Responsibles.show',
            'description'   => 'Ver en detalle cada Responsable',
        ]);
        Permission::create([
            'name'          => 'Edición de Responsables',
            'slug'          => 'Responsibles.edit',
            'description'   => 'Editar cualquier dato de un Responsable',
        ]);
        Permission::create([
            'name'          => 'API Edición de Responsables',
            'slug'          => 'Responsibles.update',
            'description'   => 'Editar cualquier dato de un Responsable',
        ]);
        Permission::create([
            'name'          => 'Eliminar Responsables',
            'slug'          => 'Responsibles.destroy',
            'description'   => 'Elimina un Responsable',
        ]);

        //### veterinarians ###
        Permission::create([
            'name'          => 'Listar Veterinarios',
            'slug'          => 'veterinarians.index',
            'description'   => 'Lista y navega todos los Veterinarios',
        ]);
        Permission::create([
            'name'          => 'Crear Veterinarios',
            'slug'          => 'veterinarians.create',
            'description'   => 'Crear un Veterinario',
        ]);
        Permission::create([
            'name'          => 'API Crear Veterinarios',
            'slug'          => 'veterinarians.store',
            'description'   => 'Crear un Veterinario',
        ]);
        Permission::create([
            'name'          => 'Ver el detalle de un Veterinario',
            'slug'          => 'veterinarians.show',
            'description'   => 'Ver en detalle cada Veterinario',
        ]);
        Permission::create([
            'name'          => 'Edición de Veterinarios',
            'slug'          => 'veterinarians.edit',
            'description'   => 'Editar cualquier dato de un Veterinario',
        ]);
        Permission::create([
            'name'          => 'API Edición de Veterinarios',
            'slug'          => 'veterinarians.update',
            'description'   => 'Editar cualquier dato de un Veterinario',
        ]);
        Permission::create([
            'name'          => 'Eliminar Veterinarios',
            'slug'          => 'veterinarians.destroy',
            'description'   => 'Elimina un Veterinario',
        ]);

        //### treatments ###
        Permission::create([
            'name'          => 'Listar Tratamientos',
            'slug'          => 'treatments.index',
            'description'   => 'Lista y navega todos los Tratamientos',
        ]);
        Permission::create([
            'name'          => 'Crear Tratamientos',
            'slug'          => 'treatments.create',
            'description'   => 'Crear un Tratamiento',
        ]);
        Permission::create([
            'name'          => 'API Crear Tratamientos',
            'slug'          => 'treatments.store',
            'description'   => 'Crear un Tratamiento',
        ]);
        Permission::create([
            'name'          => 'Ver el detalle de un Tratamiento',
            'slug'          => 'treatments.show',
            'description'   => 'Ver en detalle cada Tratamiento',
        ]);
        Permission::create([
            'name'          => 'Edición de Tratamientos',
            'slug'          => 'treatments.edit',
            'description'   => 'Editar cualquier dato de un Tratamiento',
        ]);
        Permission::create([
            'name'          => 'API Edición de Tratamientos',
            'slug'          => 'treatments.update',
            'description'   => 'Editar cualquier dato de un Tratamiento',
        ]);
        Permission::create([
            'name'          => 'Eliminar Tratamientos',
            'slug'          => 'treatments.destroy',
            'description'   => 'Elimina un Tratamiento',
        ]);

        //### causes ###
        Permission::create([
            'name'          => 'Listar Causas',
            'slug'          => 'causes.index',
            'description'   => 'Lista y navega todos los Causas',
        ]);
        Permission::create([
            'name'          => 'Crear Causas',
            'slug'          => 'causes.create',
            'description'   => 'Crear una Causa',
        ]);
        Permission::create([
            'name'          => 'API Crear Causas',
            'slug'          => 'causes.store',
            'description'   => 'Crear una Causa',
        ]);
        Permission::create([
            'name'          => 'Ver el detalle de una Causa',
            'slug'          => 'causes.show',
            'description'   => 'Ver en detalle cada Causa',
        ]);
        Permission::create([
            'name'          => 'Edición de Causas',
            'slug'          => 'causes.edit',
            'description'   => 'Editar cualquier dato de una Causa',
        ]);
        Permission::create([
            'name'          => 'API Edición de Causas',
            'slug'          => 'causes.update',
            'description'   => 'Editar cualquier dato de una Causa',
        ]);
        Permission::create([
            'name'          => 'Eliminar Causas',
            'slug'          => 'causes.destroy',
            'description'   => 'Elimina una Causa',
        ]);

        //### diagnostics ###
        Permission::create([
            'name'          => 'Listar Diagnósticos',
            'slug'          => 'diagnostics.index',
            'description'   => 'Lista y navega todos los Diagnósticos',
        ]);
        Permission::create([
            'name'          => 'Crear Diagnósticos',
            'slug'          => 'diagnostics.create',
            'description'   => 'Crear un Diagnóstico',
        ]);
        Permission::create([
            'name'          => 'API Crear Diagnósticos',
            'slug'          => 'diagnostics.store',
            'description'   => 'Crear un Diagnóstico',
        ]);
        Permission::create([
            'name'          => 'Ver el detalle de un Diagnóstico',
            'slug'          => 'diagnostics.show',
            'description'   => 'Ver en detalle cada Diagnóstico',
        ]);
        Permission::create([
            'name'          => 'Edición de Diagnósticos',
            'slug'          => 'diagnostics.edit',
            'description'   => 'Editar cualquier dato de un Diagnóstico',
        ]);
        Permission::create([
            'name'          => 'API Edición de Diagnósticos',
            'slug'          => 'diagnostics.update',
            'description'   => 'Editar cualquier dato de un Diagnóstico',
        ]);
        Permission::create([
            'name'          => 'Eliminar Diagnósticos',
            'slug'          => 'diagnostics.destroy',
            'description'   => 'Elimina un Diagnóstico',
        ]);

        //### agegroups ###
        Permission::create([
            'name'          => 'Listar Grupos Etarios',
            'slug'          => 'agegroups.index',
            'description'   => 'Lista y navega todos los Grupos Etarios',
        ]);
        Permission::create([
            'name'          => 'Crear Grupos Etarios',
            'slug'          => 'agegroups.create',
            'description'   => 'Crear un Grupo Etario',
        ]);
        Permission::create([
            'name'          => 'API Crear Grupos Etarios',
            'slug'          => 'agegroups.store',
            'description'   => 'Crear un Grupo Etario',
        ]);
        Permission::create([
            'name'          => 'Ver el detalle de un Grupo Etario',
            'slug'          => 'agegroups.show',
            'description'   => 'Ver en detalle cada Grupo Etario',
        ]);
        Permission::create([
            'name'          => 'Edición de Grupos Etarios',
            'slug'          => 'agegroups.edit',
            'description'   => 'Editar cualquier dato de un Grupo Etario',
        ]);
        Permission::create([
            'name'          => 'API Edición de Grupos Etarios',
            'slug'          => 'agegroups.update',
            'description'   => 'Editar cualquier dato de un Grupo Etario',
        ]);
        Permission::create([
            'name'          => 'Eliminar Grupos Etarios',
            'slug'          => 'agegroups.destroy',
            'description'   => 'Elimina un Grupo Etario',
        ]);

        //### breeds ###
        Permission::create([
            'name'          => 'Listar Razas',
            'slug'          => 'breeds.index',
            'description'   => 'Lista y navega todos los Razas',
        ]);
        Permission::create([
            'name'          => 'Crear Razas',
            'slug'          => 'breeds.create',
            'description'   => 'Crear una Raza',
        ]);
        Permission::create([
            'name'          => 'API Crear Razas',
            'slug'          => 'breeds.store',
            'description'   => 'Crear una Raza',
        ]);
        Permission::create([
            'name'          => 'Ver el detalle de una Raza',
            'slug'          => 'breeds.show',
            'description'   => 'Ver en detalle cada Raza',
        ]);
        Permission::create([
            'name'          => 'Edición de Razas',
            'slug'          => 'breeds.edit',
            'description'   => 'Editar cualquier dato de una Raza',
        ]);
        Permission::create([
            'name'          => 'API Edición de Razas',
            'slug'          => 'breeds.update',
            'description'   => 'Editar cualquier dato de una Raza',
        ]);
        Permission::create([
            'name'          => 'Eliminar Razas',
            'slug'          => 'breeds.destroy',
            'description'   => 'Elimina una Raza',
        ]);

        //### examns ###
        Permission::create([
            'name'          => 'Listar Exámenes',
            'slug'          => 'examns.index',
            'description'   => 'Lista y navega todos los Exámenes',
        ]);
        Permission::create([
            'name'          => 'Crear Exámenes',
            'slug'          => 'examns.create',
            'description'   => 'Crear un Examen',
        ]);
        Permission::create([
            'name'          => 'API Crear Exámenes',
            'slug'          => 'examns.store',
            'description'   => 'Crear un Examen',
        ]);
        Permission::create([
            'name'          => 'Ver el detalle de un Examen',
            'slug'          => 'examns.show',
            'description'   => 'Ver en detalle cada Examen',
        ]);
        Permission::create([
            'name'          => 'Edición de Exámenes',
            'slug'          => 'examns.edit',
            'description'   => 'Editar cualquier dato de un Examen',
        ]);
        Permission::create([
            'name'          => 'API Edición de Exámenes',
            'slug'          => 'examns.update',
            'description'   => 'Editar cualquier dato de un Examen',
        ]);
        Permission::create([
            'name'          => 'Eliminar Exámenes',
            'slug'          => 'examns.destroy',
            'description'   => 'Elimina un Examen',
        ]);

        //### vitamins ###
        Permission::create([
            'name'          => 'Listar Vitaminas',
            'slug'          => 'vitamins.index',
            'description'   => 'Lista y navega todos los Vitaminas',
        ]);
        Permission::create([
            'name'          => 'Crear Vitaminas',
            'slug'          => 'vitamins.create',
            'description'   => 'Crear una Vitamina',
        ]);
        Permission::create([
            'name'          => 'API Crear Vitaminas',
            'slug'          => 'vitamins.store',
            'description'   => 'Crear una Vitamina',
        ]);
        Permission::create([
            'name'          => 'Ver el detalle de una Vitamina',
            'slug'          => 'vitamins.show',
            'description'   => 'Ver en detalle cada Vitamina',
        ]);
        Permission::create([
            'name'          => 'Edición de Vitaminas',
            'slug'          => 'vitamins.edit',
            'description'   => 'Editar cualquier dato de una Vitamina',
        ]);
        Permission::create([
            'name'          => 'API Edición de Vitaminas',
            'slug'          => 'vitamins.update',
            'description'   => 'Editar cualquier dato de una Vitamina',
        ]);
        Permission::create([
            'name'          => 'Eliminar Vitaminas',
            'slug'          => 'vitamins.destroy',
            'description'   => 'Elimina una Vitamina',
        ]);

        //### dewormers ###
        Permission::create([
            'name'          => 'Listar Desparasitantes',
            'slug'          => 'dewormers.index',
            'description'   => 'Lista y navega todos los Desparasitantes',
        ]);
        Permission::create([
            'name'          => 'Crear Desparasitantes',
            'slug'          => 'dewormers.create',
            'description'   => 'Crear un Desparasitante',
        ]);
        Permission::create([
            'name'          => 'API Crear Desparasitantes',
            'slug'          => 'dewormers.store',
            'description'   => 'Crear un Desparasitante',
        ]);
        Permission::create([
            'name'          => 'Ver el detalle de un Desparasitante',
            'slug'          => 'dewormers.show',
            'description'   => 'Ver en detalle cada Desparasitante',
        ]);
        Permission::create([
            'name'          => 'Edición de Desparasitantes',
            'slug'          => 'dewormers.edit',
            'description'   => 'Editar cualquier dato de un Desparasitante',
        ]);
        Permission::create([
            'name'          => 'API Edición de Desparasitantes',
            'slug'          => 'dewormers.update',
            'description'   => 'Editar cualquier dato de un Desparasitante',
        ]);
        Permission::create([
            'name'          => 'Eliminar Desparasitantes',
            'slug'          => 'dewormers.destroy',
            'description'   => 'Elimina un Desparasitante',
        ]);

        //### vaccinations ###
        Permission::create([
            'name'          => 'Listar Vacunas',
            'slug'          => 'vaccinations.index',
            'description'   => 'Lista y navega todos los Vacunas',
        ]);
        Permission::create([
            'name'          => 'Crear Vacunas',
            'slug'          => 'vaccinations.create',
            'description'   => 'Crear una Vacuna',
        ]);
        Permission::create([
            'name'          => 'API Crear Vacunas',
            'slug'          => 'vaccinations.store',
            'description'   => 'Crear una Vacuna',
        ]);
        Permission::create([
            'name'          => 'Ver el detalle de una Vacuna',
            'slug'          => 'vaccinations.show',
            'description'   => 'Ver en detalle cada Vacuna',
        ]);
        Permission::create([
            'name'          => 'Edición de Vacunas',
            'slug'          => 'vaccinations.edit',
            'description'   => 'Editar cualquier dato de una Vacuna',
        ]);
        Permission::create([
            'name'          => 'API Edición de Vacunas',
            'slug'          => 'vaccinations.update',
            'description'   => 'Editar cualquier dato de una Vacuna',
        ]);
        Permission::create([
            'name'          => 'Eliminar Vacunas',
            'slug'          => 'vaccinations.destroy',
            'description'   => 'Elimina una Vacuna',
        ]);
    }
}
