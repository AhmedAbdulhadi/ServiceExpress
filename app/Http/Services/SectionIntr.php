<?php
	use Illuminate\Http\Request;
interface SectionIntr
{
	public static function getOneSectionById($id);//get_one_sections
	public static function getAllSection();//getAllSection
	public static function createSection(Request $request);//create_Section
	public static function deleteSection($id);//delete_Section
	public static function updateSection(Request $request,$id);//update_section
	public static function getSectionWithServices(Request $request,$id);//get_one_user
//	public static function getSectionsWithServices(Request $request , $id);//get_sectionid
	public static function getSectionWithServicesStatus(Request $request);//section_with_services


}